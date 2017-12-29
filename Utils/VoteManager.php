<?php

namespace Lt\UpvoteBundle\Utils;

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
    protected $voteAggregateRepository;

    public function __construct(
        EntityManager $entityManager
    ) {
        $this->entityManager = $entityManager;
        $this->voteRepository = $entityManager->getRepository(Vote::class);
        $this->voteAggregateRepository = $entityManager->getRepository(VoteAggregate::class);
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

    public function reset($subjectId, $subjectType)
    {
        $vote = $this->voteAggregateRepository->findOneBySubject($subjectId, $subjectType);
        if ($vote !== null) {
            $this->entityManager->remove($vote);
        }
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
        $vote = $this->getVote($voteAggregate, $userId, $visitorId);
        $existingVoteValue = $vote->getVote();
        $vote->setVote($voteValue);
        $voteAggregate->setTotal($voteAggregate->getTotal() - $existingVoteValue + $voteValue);

        $vote->setVoteAggregate($voteAggregate);
        $voteAggregate->addVote($vote);


        $this->entityManager->persist($vote);
        $this->entityManager->persist($voteAggregate);
        $this->entityManager->flush();
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
            $voteAggregate
                ->setSubjectId($subjectId)
                ->setSubjectType($subjectType);
            $this->entityManager->persist($voteAggregate);
        }
        return $voteAggregate;
    }
}
