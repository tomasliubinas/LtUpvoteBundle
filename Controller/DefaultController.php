<?php

namespace Lt\UpvoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Lt\UpvoteBundle\Utils\UserProvider;
use Lt\UpvoteBundle\Utils\VisitorIdentifier;
use Lt\UpvoteBundle\Model\VoteManager;

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

    public function __construct(
        UserProvider $userProvider,
        VisitorIdentifier $visitorIdentifier,
        VoteManager $voteManager
    ) {
        $this->userProvider = $userProvider;
        $this->visitorIdentifier = $visitorIdentifier;
        $this->voteManager = $voteManager;
    }

    /**
     * Registers an upvote for the identified user or visitor.
     * Depending on current upvote status adds 1 or 2 to total vote counts for the given subject.
     * Fails in case the upvote already exists.
     *
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     *
     * @return Response
     */
    public function upvoteAction(Request $request, $subjectId, $subjectType)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->upvote($subjectId, $subjectType, $userId, $visitorId);
        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode('upvoted'));
    }

    /**
     * Registers a downvote for the identified user or visitor.
     * Depending on current upvote status subtracts 1 or 2 from total vote counts for the given subject.
     * Fails in case the downvote already exists.
     *
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     *
     * @return Response
     */
    public function downvoteAction(Request $request, $subjectId, $subjectType)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->downvote($subjectId, $subjectType, $userId, $visitorId);
        $this->getDoctrine()->getManager()->flush();
        return new Response('downvoted');
    }

    /**
     * Resets any existing upvote or downvote for subject.
     *
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     *
     * @return Response
     */
    public function resetAction(Request $request, $subjectId, $subjectType)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->reset($subjectId, $subjectType, $userId, $visitorId);
        $this->getDoctrine()->getManager()->flush();
        return new Response('reset');
    }

    public function renderUpvote(Request $request, $subjectId, $subjectType)
    {
        $totalValue = $this->voteManager->getTotalValue($subjectType, $subjectId);

        $params = [
            'id' => $subjectId,
            'type' => $subjectType,
            'total' => $totalValue,
        ];
        return $this->render('@LtUpvote/Default/upvote.html.twig', $params);
    }
}
