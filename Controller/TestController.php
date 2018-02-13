<?php

namespace Lt\UpvoteBundle\Controller;

use Lt\UpvoteBundle\Model\VoteManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TestController extends Controller
{
    /**
     * @var VoteManager
     */
    private $voteManager;

    public function __construct(VoteManager $voteManager)
    {
        $this->voteManager = $voteManager;
    }

    public function indexAction()
    {
        $env = $this->get('kernel')->getEnvironment();

        if ($env !== 'dev' && $env !== 'test') {
            throw new AccessDeniedHttpException('Access denied in production environment');
        }

        return $this->render('LtUpvoteBundle:Default:test.html.twig', ['voteManager' => $this->voteManager]);
    }
}