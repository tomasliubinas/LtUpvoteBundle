<?php

namespace Lt\UpvoteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use Lt\UpvoteBundle\Entity\VoteAggregate;

class VoteAggregateRepository extends EntityRepository
{
    /**
     * Finds one Vote Aggregate By Subject (id and type)
     *
     * @param string $subjectType
     * @param string $subjectId
     * @return null|VoteAggregate
     */
    public function findOneBySubject($subjectType, $subjectId)
    {
        return $this->findOneBy(['subjectType' => $subjectType, 'subjectId' => $subjectId]);
    }
}
