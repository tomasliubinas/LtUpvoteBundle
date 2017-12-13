<?php

namespace Lt\UpvoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LtUpvoteBundle:Default:index.html.twig');
    }

    /**
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     * @return Response
     */
    public function upvoteAction(Request $request, $subjectId, $subjectType)
    {
        return new Response('upvoted');
    }

    /**
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     * @return Response
     */
    public function downvoteAction(Request $request, $subjectId, $subjectType)
    {
        return new Response("downvoted");
    }

    /**
     * @param Request $request
     * @param string $subjectId
     * @param string $subjectType
     * @return Response
     */
    public function resetAction(Request $request, $subjectId, $subjectType)
    {
        return new Response('reset');
    }
}
