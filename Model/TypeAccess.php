<?php

namespace Lt\UpvoteBundle\Model;

class TypeAccess
{
    const VISIBLE_UPVOTE = 'show_upvote';
    const VISIBLE_DOWNVOTE = 'show_downvote';
    const ALLOW_ANONYMOUS_UPVOTE = 'allow_anonymous_upvote';
    const ALLOW_ANONYMOUS_DOWNVOTE = 'allow_anonymous_downvote';


    /**
     * @param string $type
     * @param array $availableTypes
     *
     * @return bool
     */
    public function isTypeAvailable($type, $availableTypes)
    {
        return isset($availableTypes[$type]);
    }

    /**
     * @param string $type
     * @param array $availableTypes
     *
     * @return bool
     */
    public function isVisibleUpvote($type, $availableTypes)
    {
        return $this->isTypeAvailable($type, $availableTypes) && $availableTypes[$type][self::VISIBLE_UPVOTE];
    }

    /**
     * @param string $type
     * @param array $availableTypes
     *
     * @return bool
     */
    public function isVisibleDownvote($type, $availableTypes)
    {
        return $this->isTypeAvailable($type, $availableTypes) && $availableTypes[$type][self::VISIBLE_DOWNVOTE];
    }

    /**
     * @param string $type
     * @param array $availableTypes
     *
     * @return bool
     */
    public function canUpvoteAnonymous($type, $availableTypes)
    {
        return $this->isTypeAvailable($type, $availableTypes) && $availableTypes[$type][self::ALLOW_ANONYMOUS_UPVOTE];
    }

    /**
     * @param string $type
     * @param array $availableTypes
     *
     * @return bool
     */
    public function canDownvoteAnonymous($type, $availableTypes)
    {
        return $this->isTypeAvailable($type, $availableTypes) && $availableTypes[$type][self::ALLOW_ANONYMOUS_DOWNVOTE];
    }
}