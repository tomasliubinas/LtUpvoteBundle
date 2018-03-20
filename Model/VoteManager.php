<?php

namespace Lt\UpvoteBundle\Model;

use Doctrine\ORM\EntityManager;
use Lt\UpvoteBundle\Entity\Vote;
use Lt\UpvoteBundle\Entity\VoteAggregate;
use Lt\UpvoteBundle\Exception\PermissionViolationException;
use Lt\UpvoteBundle\Exception\UniqueConstraintViolationException;
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
     * @var array
     */
    private $types;

    /**
     * @param EntityManager $entityManager
     * @param VoteRepository $voteRepository
     * @param VoteAggregateRepository $voteAggregateRepository
     * @param array $types
     */
    public function __construct(
        EntityManager $entityManager,
        VoteRepository $voteRepository,
        VoteAggregateRepository $voteAggregateRepository,
        array $types
    ) {
        $this->voteRepository = $voteRepository;
        $this->voteAggregateRepository = $voteAggregateRepository;
        $this->entityManager = $entityManager;
        $this->types = $types;
    }

    /**
     * Set available subject type configuration. Provided in bundle configuration array format.
     *
     * @param array $types
     *
     * @return VoteManager
     */
    public function setTypes($types)
    {
        $this->types = $types;
        return $this;
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
    public function getTotalValue($subjectType, $subjectId)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($voteAggregate !== null) {
            return $voteAggregate->getTotalValue();
        }

        return 0;
    }

    /**
     * Find Vote by subject and visitor
     *
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     *
     * @throws PermissionViolationException
     *
     * @return Vote|null
     */
    public function findVote($subjectType, $subjectId, $userId, $visitorId)
    {
        if (!$this->isTypeAvailable($subjectType)) {
            throw new PermissionViolationException(sprintf("Subject type '%s' is not available. Please provide configuration for this type", $subjectType));
        }

        if ($userId !== null) {
            return $this->voteRepository->findOneBySubjectAndUserId($subjectType, $subjectId, $userId);
        } else {
            return $this->voteRepository->findOneBySubjectAndVisitorId($subjectType, $subjectId, $visitorId);
        }
    }

    /**
     * Find VoteAggregate by subject and visitor
     *
     * @param string $subjectType
     * @param string $subjectId
     *
     * @throws PermissionViolationException
     *
     * @return VoteAggregate|null
     */
    public function findVoteAggregate($subjectType, $subjectId)
    {
        if (!$this->isTypeAvailable($subjectType)) {
            throw new PermissionViolationException(sprintf("Subject type '%s' is not available. Please provide configuration for this type", $subjectType));
        }

        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectType, $subjectId);
        return $voteAggregate;
    }

    /**
     * Checks if this subject type is available
     *
     * @param string $subjectType
     *
     * @return bool
     */
    public function isTypeAvailable($subjectType)
    {
        return isset($this->types[$subjectType]);
    }


    /**
     * @param int $voteValue
     * @param string $subjectType
     * @param string $subjectId
     * @param string|null $userId
     * @param string $visitorId
     *
     * @throws UniqueConstraintViolationException
     */
    protected function castVote($voteValue, $subjectType, $subjectId, $userId, $visitorId)
    {
        $vote = $this->getVote($subjectType, $subjectId, $userId, $visitorId);
        $currentVoteValue = $vote->getValue();
        if ($currentVoteValue === $voteValue) {
            throw new UniqueConstraintViolationException('Can not cast the same vote more than once');
        }
        $vote->setValue($voteValue);

        $voteAggregate = $vote->getVoteAggregate();
        $voteAggregate->setTotalValue($voteAggregate->getTotalValue() - $currentVoteValue + $voteValue);
        if ($voteValue > 0) {
            $voteAggregate->setTotalUpvotes($voteAggregate->getTotalUpvotes() + 1);
        } elseif ($voteValue < 0 && $currentVoteValue > 0) {
            $voteAggregate->setTotalUpvotes($voteAggregate->getTotalUpvotes() - 1);
        }

        if ($voteValue < 0) {
            $voteAggregate->setTotalDownvotes($voteAggregate->getTotalDownvotes() + 1);
        } elseif ($voteValue > 0 && $currentVoteValue < 0) {
            $voteAggregate->setTotalDownvotes($voteAggregate->getTotalDownvotes() - 1);
        }

        $vote->setUpdatedAt(new \DateTime());

        $this->entityManager->persist($vote);
        $this->entityManager->persist($voteAggregate);
    }

    /**
     * Get Vote object by subject and visitor
     *
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
        $voteAggregate = $this->findVoteAggregate($subjectType, $subjectId);
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
