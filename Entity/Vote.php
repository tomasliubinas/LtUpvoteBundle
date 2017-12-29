<?php

namespace Lt\UpvoteBundle\Entity;

/**
 * Vote Entity represents a single upvote or download
 */
class Vote
{
    /**
     * @var int
     */
    private $id;

    /**
     * @var int
     */
    private $voteAggregateId;

    /**
     * @var string
     */
    private $userId;

    /**
     * @var string
     */
    private $visitorId;

    /**
     * @var int
     */
    private $vote;

    /**
     * @var \DateTime
     */
    private $updatedAt;

    /**
     * @var VoteAggregate
     */
    private $voteAggregate;

    public function __construct()
    {
        $this->updatedAt = new \DateTime();
    }

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
     * Set voteAggregateId
     *
     * @param integer $voteAggregateId
     *
     * @return Vote
     */
    public function setVoteAggregateId($voteAggregateId)
    {
        $this->voteAggregateId = $voteAggregateId;

        return $this;
    }

    /**
     * Get voteAggregateId
     *
     * @return int
     */
    public function getVoteAggregateId()
    {
        return $this->voteAggregateId;
    }

    /**
     * Set userId
     *
     * @param string $userId
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
     * @return string
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
     * Set vote
     *
     * @param integer $vote
     *
     * @return Vote
     */
    public function setVote($vote)
    {
        $this->vote = $vote;

        return $this;
    }

    /**
     * Get vote
     *
     * @return int
     */
    public function getVote()
    {
        return $this->vote;
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
