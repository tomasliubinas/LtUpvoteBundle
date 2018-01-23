<?php

namespace Lt\UpvoteBundle\Model;

use Doctrine\ORM\EntityManager;
use Lt\UpvoteBundle\Entity\Vote;
use Lt\UpvoteBundle\Entity\VoteAggregate;
use Lt\UpvoteBundle\Repository\VoteAggregateRepository;
use Lt\UpvoteBundle\Repository\VoteRepository;

class VoteManager
{
    /**
     * @var EntityManager
     */
    private $entityManager;
    
    /**
     * @var VoteRepository
     */
    protected $voteRepository;
    
    /**
     * @var VoteAggregateRepository
     */
    private $voteAggregateRepository;

    /**
     * @param EntityManager $entityManager
     * @param VoteRepository $voteRepository
     * @param VoteAggregateRepository $voteAggregateRepository
     */
    public function __construct(
        EntityManager $entityManager,
        VoteRepository $voteRepository,
        VoteAggregateRepository $voteAggregateRepository
    ) {
        $this->voteRepository = $voteRepository;
        $this->voteAggregateRepository = $voteAggregateRepository;
        $this->entityManager = $entityManager;
    }

    /**
     * Performs upvote on the subject.
     * Adds 1-2 votes to the total value.
     *
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     */
    public function upvote($subjectType, $subjectId, $userId, $visitorId)
    {
        $this->castVote(+1, $subjectType, $subjectId, $userId, $visitorId);
    }

    /**
     * Performs downvote on the subject
     * Subtracts 1-2 votes from the total value.
     *
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     */
    public function downvote($subjectType, $subjectId, $userId, $visitorId)
    {
        $this->castVote(-1, $subjectType, $subjectId, $userId, $visitorId);
    }

    /**
     * Removes any existing vote.
     * Subtract the vote from the total value.
     *
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     */
    public function reset($subjectType, $subjectId, $userId, $visitorId)
    {
        $vote = $this->findVote($subjectType, $subjectId, $userId, $visitorId);
        if ($vote !== null) {
            $voteAggregate = $vote->getVoteAggregate();
            $this->subtractFromAggregate($voteAggregate, $vote);
            $this->entityManager->remove($vote);
        }
    }

    /**
     * Get total vote result for subject
     *
     * @param string $subjectType
     * @param string $subjectId
     * @return int
     */
    public function getTotal($subjectType, $subjectId)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($voteAggregate !== null) {
            return $voteAggregate->getTotalValue();
        }

        return 0;
    }

    /**
     * @param int $voteValue
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     */
    protected function castVote($voteValue, $subjectType, $subjectId, $userId, $visitorId)
    {
        $vote = $this->getVote($subjectType, $subjectId, $userId, $visitorId);
        $existingVoteValue = $vote->getValue();
        $vote->setValue($voteValue);

        $voteAggregate = $vote->getVoteAggregate();
        $voteAggregate->setTotalValue($voteAggregate->getTotalValue() - $existingVoteValue + $voteValue);
        $voteAggregate->setTotalUpvotes($voteAggregate->getTotalUpvotes() - $existingVoteValue + $voteValue);
        $voteAggregate->setTotalDownvotes($voteAggregate->getTotalUpvotes() + $existingVoteValue - $voteValue);

        $this->entityManager->persist($vote);
        $this->entityManager->persist($voteAggregate);
    }

    /**
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     *
     * @return Vote
     */
    protected function getVote($subjectType, $subjectId, $userId, $visitorId)
    {
        $vote = $this->findVote($subjectType, $subjectId, $userId, $visitorId);
        if ($vote === null) {
            $vote = $this->createVote($subjectType, $subjectId, $userId, $visitorId);
        }
        return $vote;
    }

    /**
     * @param string $subjectType
     * @param string $subjectId
     *
     * @return VoteAggregate|null
     */
    protected function getVoteAggregate($subjectType, $subjectId)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectType, $subjectId);
        if ($voteAggregate === null) {
            $voteAggregate = (new VoteAggregate())
                ->setSubjectId($subjectId)
                ->setSubjectType($subjectType);
        }
        return $voteAggregate;
    }

    /**
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     *
     * @return Vote|null
     */
    protected function findVote($subjectType, $subjectId, $userId, $visitorId)
    {
        if ($userId !== null) {
            return $this->voteRepository->findOneBySubjectAndUserId($subjectType, $subjectId, $userId);
        } else {
            return $this->voteRepository->findOneBySubjectAndVisitorId($subjectType, $subjectId, $visitorId);
        }
    }

    /**
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     *
     * @return Vote
     */
    protected function createVote($subjectType, $subjectId, $userId, $visitorId)
    {
        $vote = (new Vote())
            ->setUserId($userId)
            ->setVisitorId($visitorId)
            ->setVoteAggregate($this->getVoteAggregate($subjectType, $subjectId))
        ;
        return $vote;
    }

    /**
     * @param VoteAggregate $voteAggregate
     * @param Vote $vote
     */
    protected function subtractFromAggregate(VoteAggregate $voteAggregate, Vote $vote)
    {
        $voteAggregate->setTotalValue($voteAggregate->getTotalValue() - $vote->getValue());

        if ($vote->getValue() > 0) {
            $voteAggregate->setTotalUpvotes($voteAggregate->getTotalUpvotes() - 1);
        }

        if ($vote->getValue() < 0) {
            $voteAggregate->setTotalDownvotes($voteAggregate->getTotalDownvotes() - 1);
        }
    }
}
