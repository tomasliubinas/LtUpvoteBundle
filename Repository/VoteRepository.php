<?php

namespace Lt\UpvoteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lt\UpvoteBundle\Entity\Vote;

class VoteRepository extends EntityRepository
{
    /**
     * @param integer $aggregateId
     * @param integer $userId
     * @return null|Vote
     */
    public function findOneByAggregateAndUser($aggregateId, $userId)
    {
        return $this->findOneBy(['aggregateId' => $aggregateId, 'userId' => $userId]);
    }

    /**
     * @param integer $aggregateId
     * @param integer $visitorId
     * @return null|Vote
     */
    public function findOneByAggregateAndVisitor($aggregateId, $visitorId)
    {
        return $this->findOneBy(['aggregateId' => $aggregateId, 'userId' => null, 'visitorId' => $visitorId]);
    }
}
