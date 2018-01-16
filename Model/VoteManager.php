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
     * @param string $subjectId
     * @param string $subjectType
     * @param string $userId
     * @param string $visitorId
     *
     *
     */
    public function upvote($subjectId, $subjectType, $userId, $visitorId)
    {
        $this->castVote(+1, $subjectId, $subjectType, $userId, $visitorId);
    }

    /**
     * @param string $subjectId
     * @param string $subjectType
     * @param string $userId
     * @param string $visitorId
     */
    public function downvote($subjectId, $subjectType, $userId, $visitorId)
    {
        $this->castVote(-1, $subjectId, $subjectType, $userId, $visitorId);
    }

    public function reset($subjectId, $subjectType, $userId, $visitorId)
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
     * @param string $subjectId
     * @param string $subjectType
     * @return int
     */
    public function getTotal($subjectId, $subjectType)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($voteAggregate !== null) {
            return $voteAggregate->getTotalValue();
        }

        return 0;
    }

    /**
     * @param int $voteValue
     * @param string $subjectId
     * @param string $subjectType
     * @param int $userId
     * @param string $visitorId
     */
    protected function castVote($voteValue, $subjectId, $subjectType, $userId, $visitorId)
    {
        $voteAggregate = $this->getVoteAggregate($subjectId, $subjectType);
        $vote = $this->getVote($subjectId, $subjectType, $userId, $visitorId);
        $existingVoteValue = $vote->getValue();
        $vote->setValue($voteValue);
        $voteAggregate->setTotalValue($voteAggregate->getTotalValue() - $existingVoteValue + $voteValue);

        $vote->setVoteAggregate($voteAggregate);

        $this->entityManager->persist($voteAggregate);
        $this->entityManager->persist($vote);
    }

    /**
     * @param string $subjectType
     * @param string $subjectId
     * @param string $userId
     * @param string $visitorId
     * @return Vote
     */
    protected function getVote($subjectType, $subjectId, $userId, $visitorId)
    {
        $vote = $this->findVote($subjectType, $subjectId, $userId, $visitorId);
        if ($vote === null) {
            $vote = $this->createVote($userId);
        }

        return $vote;
    }

    /**
     * @param string $subjectId
     * @param string $subjectType
     * @return VoteAggregate|null
     */
    protected function getVoteAggregate($subjectId, $subjectType)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($voteAggregate === null) {
            $voteAggregate = new VoteAggregate();
            $voteAggregate
                ->setSubjectId($subjectId)
                ->setSubjectType($subjectType);
        }
        return $voteAggregate;
    }

    /**
     * @param $subjectType
     * @param $subjectId
     * @param $userId
     * @param $visitorId
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
     * @param string $userId
     *
     * @return Vote
     */
    protected function createVote($userId)
    {
        $vote = new Vote();
        if ($userId) {
            $vote->setUserId($userId);
        }
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
