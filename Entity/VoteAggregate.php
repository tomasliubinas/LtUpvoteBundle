<?php

namespace Lt\UpvoteBundle\Entity;

/**
 * VoteAggregate Entity represents vote totals for given subject
 */
class VoteAggregate
{

    /**
     * @var string
     */
    private $subjectType;

    /**
     * @var string
     */
    private $subjectId;

    /**
     * @var integer
     */
    private $totalValue;

    /**
     * @var integer
     */
    private $totalUpvotes;

    /**
     * @var integer
     */
    private $totalDownvotes;

    /**
     * @var integer
     */
    private $id;


    /**
     * Set subjectType
     *
     * @param string $subjectType
     *
     * @return VoteAggregate
     */
    public function setSubjectType($subjectType)
    {
        $this->subjectType = $subjectType;

        return $this;
    }

    /**
     * Get subjectType
     *
     * @return string
     */
    public function getSubjectType()
    {
        return $this->subjectType;
    }

    /**
     * Set subjectId
     *
     * @param string $subjectId
     *
     * @return VoteAggregate
     */
    public function setSubjectId($subjectId)
    {
        $this->subjectId = $subjectId;

        return $this;
    }

    /**
     * Get subjectId
     *
     * @return string
     */
    public function getSubjectId()
    {
        return $this->subjectId;
    }

    /**
     * Set totalValue
     *
     * @param integer $totalValue
     *
     * @return VoteAggregate
     */
    public function setTotalValue($totalValue)
    {
        $this->totalValue = $totalValue;

        return $this;
    }

    /**
     * Get totalValue
     *
     * @return integer
     */
    public function getTotalValue()
    {
        return $this->totalValue;
    }

    /**
     * Set totalUpvotes
     *
     * @param integer $totalUpvotes
     *
     * @return VoteAggregate
     */
    public function setTotalUpvotes($totalUpvotes)
    {
        $this->totalUpvotes = $totalUpvotes;

        return $this;
    }

    /**
     * Get totalUpvotes
     *
     * @return integer
     */
    public function getTotalUpvotes()
    {
        return $this->totalUpvotes;
    }

    /**
     * Set totalDownvotes
     *
     * @param integer $totalDownvotes
     *
     * @return VoteAggregate
     */
    public function setTotalDownvotes($totalDownvotes)
    {
        $this->totalDownvotes = $totalDownvotes;

        return $this;
    }

    /**
     * Get totalDownvotes
     *
     * @return integer
     */
    public function getTotalDownvotes()
    {
        return $this->totalDownvotes;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }
}
