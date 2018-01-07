<?php

namespace Lt\UpvoteBundle\Entity;

/**
 * Vote Entity represents a single upvote or download
 */
class Vote
{
    
    /**
     * @var integer
     */
    private $userId;

    /**
     * @var string
     */
    private $visitorId;

    /**
     * @var integer
     */
    private $value;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var integer
     */
    private $id;

    /**
     * @var \Lt\UpvoteBundle\Entity\VoteAggregate
     */
    private $voteAggregate;


    /**
     * Set userId
     *
     * @param integer $userId
     *
     * @return Vote
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set visitorId
     *
     * @param string $visitorId
     *
     * @return Vote
     */
    public function setVisitorId($visitorId)
    {
        $this->visitorId = $visitorId;

        return $this;
    }

    /**
     * Get visitorId
     *
     * @return string
     */
    public function getVisitorId()
    {
        return $this->visitorId;
    }

    /**
     * Set value
     *
     * @param integer $value
     *
     * @return Vote
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     *
     * @return Vote
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
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
     * Set voteAggregate
     *
     * @param VoteAggregate $voteAggregate
     *
     * @return Vote
     */
    public function setVoteAggregate(VoteAggregate $voteAggregate = null)
    {
        $this->voteAggregate = $voteAggregate;

        return $this;
    }

    /**
     * Get voteAggregate
     *
     * @return VoteAggregate
     */
    public function getVoteAggregate()
    {
        return $this->voteAggregate;
    }
}
