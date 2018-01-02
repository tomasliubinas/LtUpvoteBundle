<?php

namespace Lt\UpvoteBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

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
    private $totalUserUpvotes;

    /**
     * @var integer
     */
    private $totalUserDownvotes;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var Collection
     */
    private $votes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->votes = new ArrayCollection();
    }

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
     * Set totalUserUpvotes
     *
     * @param integer $totalUserUpvotes
     *
     * @return VoteAggregate
     */
    public function setTotalUserUpvotes($totalUserUpvotes)
    {
        $this->totalUserUpvotes = $totalUserUpvotes;

        return $this;
    }

    /**
     * Get totalUserUpvotes
     *
     * @return integer
     */
    public function getTotalUserUpvotes()
    {
        return $this->totalUserUpvotes;
    }

    /**
     * Set totalUserDownvotes
     *
     * @param integer $totalUserDownvotes
     *
     * @return VoteAggregate
     */
    public function setTotalUserDownvotes($totalUserDownvotes)
    {
        $this->totalUserDownvotes = $totalUserDownvotes;

        return $this;
    }

    /**
     * Get totalUserDownvotes
     *
     * @return integer
     */
    public function getTotalUserDownvotes()
    {
        return $this->totalUserDownvotes;
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

    /**
     * Add vote
     *
     * @param Vote $vote
     *
     * @return VoteAggregate
     */
    public function addVote(Vote $vote)
    {
        $this->votes[] = $vote;

        return $this;
    }

    /**
     * Remove vote
     *
     * @param Vote $vote
     */
    public function removeVote(Vote $vote)
    {
        $this->votes->removeElement($vote);
    }

    /**
     * Get votes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getVotes()
    {
        return $this->votes;
    }
}
