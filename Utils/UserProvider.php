<?php

namespace Lt\UpvoteBundle\Utils;

use Symfony\Component\Security\Core\Authentication\Token\AnonymousToken;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;
use Symfony\Component\Security\Core\Role\RoleInterface;
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
     * @return int|null
     */
    public function getUserId()
    {
        $user = $this->getUser();

        if ($user !== null) {
            return $user->getId();
        }

        return null;
    }

    /**
     * @return null|RoleInterface[]
     */
    public function getRoles()
    {
        $token = $this->tokenStorage->getToken();
        if ($token !== null) {
            return $token->getRoles();
        }

        return null;
    }
}
