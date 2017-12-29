<?php

namespace Lt\UpvoteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lt\UpvoteBundle\Entity\Vote;

class VoteRepository extends EntityRepository
{
    /**
     * @param integer $voteAggregateId
     * @param integer $userId
     * @return null|Vote
     */
    public function findOneByAggregateAndUser($voteAggregateId, $userId)
    {
        return $this->findOneBy(['voteAggregateId' => $voteAggregateId, 'userId' => $userId]);
    }

    /**
     * @param integer $voteAggregateId
     * @param integer $visitorId
     * @return null|Vote
     */
    public function findOneByAggregateAndVisitor($voteAggregateId, $visitorId)
    {
        return $this->findOneBy(['voteAggregateId' => $voteAggregateId, 'userId' => null, 'visitorId' => $visitorId]);
    }
}
