<?php

namespace Lt\UpvoteBundle\Utils;

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
     * @return int|null
     */
    public function getUserId()
    {
        $user = $this->getUser();
        if ($user !== null) {
            if (!method_exists($user, 'getId')) {
               return $user->getUsername();
            }
            return $user->getId();
        }
        return null;
    }

}
