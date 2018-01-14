<?php

namespace Lt\UpvoteBundle\Utils;

use Lt\UpvoteBundle\Exception\LtUpvoteBundleException;
use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\User\UserInterface;

class UserProvider
{
    /**
     * @var TokenStorage
     */
    private $tokenStorage;

    public function __construct(TokenStorage $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * Returns authorised user
     *
     * @return UserInterface|null
     */
    public function getUser()
    {
        $token = $this->tokenStorage->getToken();
        if ($token !== null) {
            if ($token instanceof AnonymousToken) {
                return null;
            }
            return $token->getUser();
        }
        return null;
    }

    /**
     * Returns authorised user identifier
     *
     * @throws LtUpvoteBundleException
     *
     * @return int|null
     */
    public function getUserId()
    {
        $user = $this->getUser();
        if ($user !== null) {
            if (!method_exists($user, 'getId')) {
               throw new LtUpvoteBundleException('User must be identified');
            }
            return $user->getId();
        }
        return null;
    }

}
