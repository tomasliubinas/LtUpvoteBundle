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

    public function testResetForUpvote()
    {
        $vote = new Vote();
        $vote
            ->setValue(1)
            ->setVisitorId('testVisitor');

        $voteAggregate = new VoteAggregate();
        $voteAggregate
            ->setTotalValue(113)
            ->setTotalUpvotes(115)
            ->setTotalDownvotes(2);
        $vote->setVoteAggregate($voteAggregate);


        $this->voteRepository->expects($this->once())->method('findOneBySubjectAndVisitorId')->with('testBlog', 15, 'testVisitor')->willReturn($vote);

        $this->entityManager->expects($this->once())->method('remove')->with($vote);

        $this->voteManager->reset(15, 'testBlog', null, 'testVisitor');

        $this->assertEquals(112, $voteAggregate->getTotalValue());
        $this->assertEquals(114, $voteAggregate->getTotalUpvotes());
        $this->assertEquals(2, $voteAggregate->getTotalDownvotes());
    }
}
