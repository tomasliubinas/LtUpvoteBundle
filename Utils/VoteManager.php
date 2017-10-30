<?php

namespace LT\UpvoteBundle\Utils;

use Doctrine\ORM\EntityManager;
use LT\UpvoteBundle\Entity\Vote;
use LT\UpvoteBundle\Entity\VoteAggregate;
use LT\UpvoteBundle\Repository\VoteAggregateRepository;
use LT\UpvoteBundle\Repository\VoteRepository;

class VoteManager
{

    /**
     * @var EntityManager
     */
    private $em;

    /**
     * @var VoteRepository
     */
    protected $voteRepository;

    /**
     * @var VoteAggregateRepository
     */
    protected $voteAggregateRepository;

    public function __construct(
        EntityManager $entityManager,
        VoteRepository $voteRepository,
        VoteAggregateRepository $voteAggregateRepository
    ) {
        $this->em = $entityManager;
        $this->voteRepository = $voteRepository;
        $this->voteAggregateRepository = $voteAggregateRepository;
    }

    public function upvote($subjectId, $subjectType, $userId, $visitorId)
    {
        $this->castVote(+1, $subjectId, $subjectType, $userId, $visitorId);
    }

    public function downvote($subjectId, $subjectType, $userId, $visitorId)
    {
        $this->castVote(-1, $subjectId, $subjectType, $userId, $visitorId);
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
        $vote = $this->getVote($voteAggregate, $visitorId, $userId);
        $existingVoteValue = $vote->getVote();
        $vote->setVote($voteValue);
        $voteAggregate->setTotal($voteAggregate->getTotal() - $existingVoteValue + $voteValue);

        $this->em->persist($vote);
        $this->em->persist($voteAggregate);
        $this->em->flush();
    }

    /**
     * @param VoteAggregate $voteAggregate
     * @param integer $userId
     * @param string $visitorId
     * @return Vote
     */
    protected function getVote($voteAggregate, $userId, $visitorId)
    {
        if ($userId !== null) {
            $vote = $this->voteRepository->findOneByAggregateAndUser($voteAggregate->getId(), $userId);
        } else {
            $vote = $this->voteRepository->findOneByAggregateAndVisitor($voteAggregate->getId(), $visitorId);
        }
        if ($vote === null) {
            $vote = new Vote();
            $vote->setVoteAggregateId($voteAggregate->getId())->setVisitorId($visitorId)->setVote(0);
            if ($userId) {
                $vote->setUserId($userId);
            }
        }

        return $vote;
    }

    /**
     * @param $subjectId
     * @param $subjectType
     * @return VoteAggregate|null
     */
    protected function getVoteAggregate($subjectId, $subjectType)
    {
        $voteAggregate = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($voteAggregate === null) {
            $voteAggregate = new VoteAggregate();
            $voteAggregate->setSubjectId($subjectId);
            $voteAggregate->setSubjectType($subjectType);
            $this->em->persist($voteAggregate);
        }
        return $voteAggregate;
    }
}
