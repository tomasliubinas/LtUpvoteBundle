<?php

namespace LT\UpvoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('LTUpvoteBundle:Default:index.html.twig');
    }
}
