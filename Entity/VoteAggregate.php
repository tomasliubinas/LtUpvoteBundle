<?php

namespace LT\UpvoteBundle\Entity;

/**
 * VoteAggregate Entity represents vote totals for given subject
 */
class VoteAggregate
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $subjectId;

    /**
     * @var string
     */
    private $subjectType;

    /**
     * @var int
     */
    private $total;

    /**
     * @var int
     */
    private $upvotes;

    /**
     * @var int
     */
    private $downvotes;

    /**
     * @var int
     */
    private $userUpvotes;

    /**
     * @var int
     */
    private $userDownvotes;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
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
     * Set total
     *
     * @param integer $total
     *
     * @return VoteAggregate
     */
    public function setTotal($total)
    {
        $this->total = $total;

        return $this;
    }

    /**
     * Get total
     *
     * @return int
     */
    public function getTotal()
    {
        return $this->total;
    }

    /**
     * Set upvotes
     *
     * @param integer $upvotes
     *
     * @return VoteAggregate
     */
    public function setUpvotes($upvotes)
    {
        $this->upvotes = $upvotes;

        return $this;
    }

    /**
     * Get upvotes
     *
     * @return int
     */
    public function getUpvotes()
    {
        return $this->upvotes;
    }

    /**
     * Set downvotes
     *
     * @param integer $downvotes
     *
     * @return VoteAggregate
     */
    public function setDownvotes($downvotes)
    {
        $this->downvotes = $downvotes;

        return $this;
    }

    /**
     * Get downvotes
     *
     * @return int
     */
    public function getDownvotes()
    {
        return $this->downvotes;
    }

    /**
     * Set userUpvotes
     *
     * @param integer $userUpvotes
     *
     * @return VoteAggregate
     */
    public function setUserUpvotes($userUpvotes)
    {
        $this->userUpvotes = $userUpvotes;

        return $this;
    }

    /**
     * Get userUpvotes
     *
     * @return int
     */
    public function getUserUpvotes()
    {
        return $this->userUpvotes;
    }

    /**
     * Set userDownvotes
     *
     * @param integer $userDownvotes
     *
     * @return VoteAggregate
     */
    public function setUserDownvotes($userDownvotes)
    {
        $this->userDownvotes = $userDownvotes;

        return $this;
    }

    /**
     * Get userDownvotes
     *
     * @return int
     */
    public function getUserDownvotes()
    {
        return $this->userDownvotes;
    }
}

