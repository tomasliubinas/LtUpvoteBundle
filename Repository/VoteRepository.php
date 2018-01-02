<?php

namespace Lt\UpvoteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lt\UpvoteBundle\Entity\Vote;

class VoteRepository extends EntityRepository
{
    /**
     * @param string $subjectType
     * @param string $subjectId
     * @param string $userId
     *
     * @return null|Vote
     */
    public function findOneBySubjectAndUserId($subjectType, $subjectId, $userId)
    {
        return $this->_em
            ->getRepository(Vote::class)
            ->createQueryBuilder('v')
            ->join('v.voteAggregate', 'va')
            ->where('va.subjectType = :subjectType')
            ->andWhere('va.subjectId = :subjectId')
            ->andWhere('v.userId = :userId')
            ->setParameter('subjectType', $subjectType)
            ->setParameter('subjectId', $subjectId)
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }

    /**
     * @param string $subjectType
     * @param string $subjectId
     * @param string $visitorId
     *
     * @return null|Vote
     */
    public function findOneBySubjectAndVisitorId($subjectType, $subjectId, $visitorId)
    {
        return $this->_em
            ->getRepository(Vote::class)
            ->createQueryBuilder('v')
            ->join('v.voteAggregate', 'va')
            ->where('va.subjectType = :subjectType')
            ->andWhere('va.subjectId = :subjectId')
            ->andWhere('v.userId is null')
            ->andWhere('v.visitorId = :visitorId')
            ->setParameter('subjectType', $subjectType)
            ->setParameter('subjectId', $subjectId)
            ->setParameter('visitorId', $visitorId)
            ->getQuery()
            ->getOneOrNullResult()
            ;
    }
}
