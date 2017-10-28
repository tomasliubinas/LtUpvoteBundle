<?php

namespace LT\UpvoteBundle\Repository;

use Doctrine\ORM\EntityRepository;
use LT\UpvoteBundle\Entity\VoteAggregate;

class VoteAggregateRepository extends EntityRepository
{
    /**
     * Finds one Vote Aggregate By Subject (id and type)
     *
     * @param string $subjectId
     * @param string|null $subjectType
     * @return null|VoteAggregate
     */
    public function findOneBySubject($subjectId, $subjectType)
    {
        return $this->findOneBy(['subjectId' => $subjectId, 'subjectType' => $subjectType]);
    }
}
