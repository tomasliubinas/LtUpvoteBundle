<?php

namespace Lt\UpvoteBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TestController extends Controller
{
    public function indexAction()
    {
        $env = $this->get('kernel')->getEnvironment();

        if ($env !== 'dev' && $env !== 'test') {
            throw new AccessDeniedHttpException('Access denied in production environment');
        }

        return new Response('Demo page');
    }
}