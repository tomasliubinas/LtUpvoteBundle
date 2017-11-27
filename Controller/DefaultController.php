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

    public function upvoteAction(Request $request)
    {
        return new Response('upvoted');
    }

    public function downvoteAction(Request $request)
    {
        return new Response('downoted');
    }

    public function resetAction(Request $request)
    {
        return new Response('reset');
    }
}
