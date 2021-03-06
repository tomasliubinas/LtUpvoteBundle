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
     * @param string $subjectType
     * @param string $subjectId
     *
     * @return Response
     */
    public function upvoteAction(Request $request, $subjectType, $subjectId)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->upvote($subjectType, $subjectId, $userId, $visitorId);
        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode('upvoted'));
    }

    /**
     * Registers a downvote for the identified user or visitor.
     * Depending on current upvote status subtracts 1 or 2 from total vote counts for the given subject.
     * Fails in case the downvote already exists.
     *
     * @param Request $request
     * @param string $subjectType
     * @param string $subjectId
     *
     * @return Response
     */
    public function downvoteAction(Request $request, $subjectType, $subjectId)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->downvote($subjectType, $subjectId, $userId, $visitorId);
        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode('downvoted'));
    }

    /**
     * Resets any existing upvote or downvote for subject.
     *
     * @param Request $request
     * @param string $subjectType
     * @param string $subjectId
     *
     * @return Response
     */
    public function resetAction(Request $request, $subjectType, $subjectId)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);
        $this->voteManager->reset($subjectType, $subjectId, $userId, $visitorId);
        $this->getDoctrine()->getManager()->flush();
        return new Response(json_encode('reset'));
    }

    /**
     * Renders frontend upvote/downvote component
     *
     * @param Request $request Request object
     * @param string $subjectType Subject context type
     * @param string $subjectId Subject ID
     * @param string $class CSS class
     *
     * @return Response
     */
    public function renderUpvote(Request $request, $subjectType, $subjectId, $class = null)
    {
        $userId = $this->userProvider->getUserId();
        $visitorId = $this->visitorIdentifier->getVisitorId($request);

        $vote = $this->voteManager->findVote($subjectType, $subjectId, $userId, $visitorId);

        $isUpvoted = false;
        $isDownvoted = false;
        if ($vote !== null) {
            $value = $vote->getValue();
            $isUpvoted = $value > 0;
            $isDownvoted = $value < 0;
        }

        $params = [
            'id' => $subjectId,
            'type' => $subjectType,
            'total_value' => $this->voteManager->getTotalValue($subjectType, $subjectId),
            'is_upvoted' => $isUpvoted,
            'is_downvoted' => $isDownvoted,
            'is_unauthenticated' => $userId === null,
            'class' => ($class !== null) ? ' ' . $class : null,
            'show_upvote' => $this->voteManager->isVisibleUpvote($subjectType, $userId),
            'show_downvote' => $this->voteManager->isVisibleDownvote($subjectType, $userId),
            'can_upvote' => $this->voteManager->canUpvote($subjectType, $userId),
            'can_downvote' => $this->voteManager->canDownvote($subjectType, $userId),
        ];
        return $this->render('@LtUpvote/Default/upvote.html.twig', $params);
    }
}
