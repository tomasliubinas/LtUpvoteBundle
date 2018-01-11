<?php

namespace Lt\UpvoteBundle\Controller;

use Lt\UpvoteBundle\Utils\UserProvider;
use Lt\UpvoteBundle\Utils\VisitorIdentifier;
use Lt\UpvoteBundle\Model\VoteManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    /**
     * @var UserProvider
     */
    private $userProvider;

    /**
     * @var VisitorIdentifier
     */
    private $visitorIdentifier;

    /**
     * @var VoteManager
     */
    private $voteManager;

    public function __construct(UserProvider $userProvider, VisitorIdentifier $visitorIdentifier, VoteManager $voteManager)
    {
        $this->userProvider = $userProvider;
        $this->voteManager = $voteManager;
        $this->visitorIdentifier = $visitorIdentifier;
    }

    /**
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     * @return Response
     */
    public function upvoteAction(Request $request, $subjectId, $subjectType)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->upvote($subjectId, $subjectType, $userId, $visitorId);
        return new Response(json_encode('upvoted'));
    }

    /**
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     * @return Response
     */
    public function downvoteAction(Request $request, $subjectId, $subjectType)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->downvote($subjectId, $subjectType, $userId, $visitorId);
        return new Response('downvoted');
    }

    /**
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     * @return Response
     */
    public function resetAction(Request $request, $subjectId, $subjectType)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->reset($subjectId, $subjectType, $userId, $visitorId);
        return new Response('reset');
    }
}
