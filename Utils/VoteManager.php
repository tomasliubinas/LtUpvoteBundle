<?php

namespace LT\UpvoteBundle\Utils;

use LT\UpvoteBundle\Repository\VoteAggregateRepository;
use LT\UpvoteBundle\Repository\VoteRepository;

class VoteManager
{
    /**
     * @var VoteRepository
     */
    private $voteRepository;

    /**
     * @var VoteAggregateRepository
     */
    private $voteAggregateRepository;

    public function __construct(VoteRepository $voteRepository, VoteAggregateRepository $voteAggregateRepository)
    {
        $this->voteRepository = $voteRepository;
        $this->voteAggregateRepository = $voteAggregateRepository;
    }

    public function upvote($subjectId, $subjectType, $userId, $visitorId)
    {
        // TODO: write logic here
    }

    public function downvote($subjectId, $subjectType, $userId, $visitorId)
    {
        // TODO: write logic here
    }

    public function getTotal($subjectId, $subjectType)
    {
        // TODO: write logic here
    }
}
