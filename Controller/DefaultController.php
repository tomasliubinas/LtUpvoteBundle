<?php

namespace LT\UpvoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LTUpvoteBundle:Default:index.html.twig');
    }

    public function upvoteAction(Request $request)
    {
        return new Response('upvoted');
    }

    public function downvoteAction(Request $request)
    {
        return new Response('downoted');
    }
}
