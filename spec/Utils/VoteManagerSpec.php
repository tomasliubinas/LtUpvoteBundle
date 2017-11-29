<?php

namespace spec\Lt\UpvoteBundle\Utils;

use Doctrine\ORM\EntityManager;
use Lt\UpvoteBundle\Entity\VoteAggregate;
use Lt\UpvoteBundle\Repository\VoteAggregateRepository;
use Lt\UpvoteBundle\Repository\VoteRepository;
use Lt\UpvoteBundle\Utils\VoteManager;
use PhpSpec\ObjectBehavior;

class VoteManagerSpec extends ObjectBehavior
{
    function let(EntityManager $entityManager, VoteRepository $voteRepository, VoteAggregateRepository $voteAggregateRepository)
    {
        $this->beConstructedWith($entityManager, $voteRepository, $voteAggregateRepository);
    }

    function it_is_initializable()
    {
        $this->shouldHaveType(VoteManager::class);    }

    function it_upvotes()
    {
        $subjectId = '15';
        $subjectType = 'blog';
        $userId = 1;
        $visitorId = '127.0.0.1';
        $this->upvote($subjectId, $subjectType, $userId, $visitorId);
    }

    function it_downvotes()
    {
        $subjectId = '1';
        $subjectType = 'blog';
        $userId = 1;
        $visitorId = '127.0.0.1';
        $this->downvote($subjectId, $subjectType, $userId, $visitorId);
    }

    function it_resets()
    {
        $subjectId = '1';
        $subjectType = 'blog';
        $userId = 1;
        $visitorId = '127.0.0.1';
        $this->reset($subjectId, $subjectType, $userId, $visitorId);
    }

    function it_gets_total(VoteAggregateRepository $voteAggregateRepository, VoteAggregate $voteAggregate)
    {
        $subjectId = '1';
        $subjectType = 'blog';
        $voteAggregateRepository->findOneBySubject($subjectId, $subjectType)->willReturn($voteAggregate);
        $voteAggregate->getTotal()->willReturn(10);
        $this->getTotal($subjectId, $subjectType)->shouldReturn(10);
    }
}
