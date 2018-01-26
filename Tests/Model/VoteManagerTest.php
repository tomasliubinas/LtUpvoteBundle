<?php

namespace Lt\UpvoteBundle\Tests\Model;

use Doctrine\ORM\EntityManager;
use Lt\UpvoteBundle\Entity\Vote;
use Lt\UpvoteBundle\Entity\VoteAggregate;
use Lt\UpvoteBundle\Model\VoteManager;
use Lt\UpvoteBundle\Repository\VoteAggregateRepository;
use Lt\UpvoteBundle\Repository\VoteRepository;
use PHPUnit\Framework\TestCase;

class VoteManagerTest extends TestCase
{
    /**
     * @var EntityManager|\PHPUnit_Framework_MockObject_MockObject
     */
    private $entityManager;

    /**
     * @var VoteRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $voteRepository;

    /**
     * @var VoteAggregateRepository|\PHPUnit_Framework_MockObject_MockObject
     */
    private $voteAggregateRepository;

    /**
     * @var VoteManager
     */
    private $voteManager;

    public function setUp()
    {
        $this->entityManager = $this->createMock(EntityManager::class);
        $this->voteRepository = $this->createMock(VoteRepository::class);
        $this->voteAggregateRepository = $this->createMock(VoteAggregateRepository::class);
        $this->voteManager = new VoteManager($this->entityManager, $this->voteRepository, $this->voteAggregateRepository);
    }

    public function testUpvoteBlank()
    {
        $voteAggregate = (new VoteAggregate())
            ->setSubjectType('testBlog')
            ->setSubjectId('testId')
            ->setTotalValue(1)
            ->setTotalUpvotes(1)
        ;

        $vote = (new Vote())
            ->setValue(1)
            ->setVisitorId('testVisitor')
            ->setVoteAggregate($voteAggregate)
        ;

        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndVisitorId')->with('testBlog', 'testId', 'testVisitor')->willReturn(null);
        $this->voteAggregateRepository->expects($this->once())->method('findOneBySubject')->with('testBlog', 'testId')->willReturn(null);

        $this->entityManager->expects($this->at(0))->method('persist')->with($vote);
        $this->entityManager->expects($this->at(1))->method('persist')->with($voteAggregate);

        $this->voteManager->upvote('testBlog', 'testId', null, 'testVisitor');
    }

    public function testUpvoteBlankVote()
    {
        $voteAggregate = (new VoteAggregate())
            ->setSubjectType('testBlog')
            ->setSubjectId('testId')
            ->setTotalValue(145)
            ->setTotalUpvotes(150)
            ->setTotalDownvotes(5)
        ;

        $vote = (new Vote())
            ->setValue(1)
            ->setVisitorId('testVisitor')
            ->setUserId('testUserId')
            ->setVoteAggregate($voteAggregate)
        ;

        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndUserId')->with('testBlog', 'testId', 'testUserId')->willReturn(null);
        $this->voteAggregateRepository->expects($this->once())->method('findOneBySubject')->with('testBlog', 'testId')->willReturn($voteAggregate);

        $this->entityManager->expects($this->at(0))->method('persist')->with($vote);

        $this->voteManager->upvote('testBlog', 'testId', 'testUserId', 'testVisitor');

        $this->assertEquals($voteAggregate->getTotalValue(), 146);
        $this->assertEquals($voteAggregate->getTotalUpvotes(), 151);
        $this->assertEquals($voteAggregate->getTotalDownvotes(), 5);
    }

    public function testUpvoteDownvoted()
    {
        $voteAggregate = (new VoteAggregate())
            ->setSubjectType('testBlog')
            ->setSubjectId('testId')
            ->setTotalValue(145)
            ->setTotalUpvotes(150)
            ->setTotalDownvotes(5)
        ;

        $vote = (new Vote())
            ->setValue(-1)
            ->setVisitorId('testVisitor')
            ->setVoteAggregate($voteAggregate)
        ;

        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndVisitorId')->with('testBlog', 'testId', 'testVisitor')->willReturn($vote);

        $this->voteManager->upvote('testBlog', 'testId', null, 'testVisitor');

        $this->assertEquals($vote->getValue(), 1);
        $this->assertEquals($voteAggregate->getTotalValue(), 147);
        $this->assertEquals($voteAggregate->getTotalUpvotes(), 151);
        $this->assertEquals($voteAggregate->getTotalDownvotes(), 4);
    }

    public function testDownvoteUpvoted()
    {
        $voteAggregate = (new VoteAggregate())
            ->setSubjectType('testBlog')
            ->setSubjectId('testId')
            ->setTotalValue(145)
            ->setTotalUpvotes(150)
            ->setTotalDownvotes(5)
        ;

        $vote = (new Vote())
            ->setValue(1)
            ->setVisitorId('testVisitor')
            ->setVoteAggregate($voteAggregate)
        ;

        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndUserId')->with('testBlog', 'testId', 'testUser')->willReturn($vote);

        $this->voteManager->downvote('testBlog', 'testId', 'testUser', 'testVisitor');

        $this->assertEquals($vote->getValue(), -1);
        $this->assertEquals($voteAggregate->getTotalValue(), 143);
        $this->assertEquals($voteAggregate->getTotalUpvotes(), 149);
        $this->assertEquals($voteAggregate->getTotalDownvotes(), 6);
    }

    public function testResetUpvoted()
    {
        $voteAggregate = (new VoteAggregate())
            ->setTotalValue(90)
            ->setTotalUpvotes(115)
            ->setTotalDownvotes(25)
        ;

        $vote = (new Vote())
            ->setValue(1)
            ->setVisitorId('testVisitor')
            ->setVoteAggregate($voteAggregate)
        ;

        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndVisitorId')->with('testBlog', 15, 'testVisitor')->willReturn($vote);
        $this->entityManager->expects($this->once())->method('remove')->with($vote);

        $this->voteManager->reset('testBlog', 15, null, 'testVisitor');

        $this->assertEquals(89, $voteAggregate->getTotalValue());
        $this->assertEquals(114, $voteAggregate->getTotalUpvotes());
        $this->assertEquals(25, $voteAggregate->getTotalDownvotes());
    }

    public function testResetDownvoted()
    {
        $voteAggregate = (new VoteAggregate())
            ->setTotalValue(90)
            ->setTotalUpvotes(115)
            ->setTotalDownvotes(25);

        $vote = (new Vote())
            ->setValue(-1)
            ->setUserId(20)
            ->setVisitorId('testVisitor')
            ->setVoteAggregate($voteAggregate)
        ;

        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndUserId')->with('testBlog', 15, 20)->willReturn($vote);
        $this->entityManager->expects($this->once())->method('remove')->with($vote);

        $this->voteManager->reset('testBlog', 15, 20, 'testVisitor');

        $this->assertEquals(91, $voteAggregate->getTotalValue());
        $this->assertEquals(115, $voteAggregate->getTotalUpvotes());
        $this->assertEquals(24, $voteAggregate->getTotalDownvotes());
    }
}
