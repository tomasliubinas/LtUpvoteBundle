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

    /**
     * Get total vote result for subject
     *
     * @param string $subjectId
     * @param string $subjectType
     * @return int
     */
    public function getTotal($subjectId, $subjectType)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($voteAggregate !== null) {
            return $voteAggregate->getTotal();
        }

        return 0;
    }
}
