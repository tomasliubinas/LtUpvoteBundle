<?php

namespace Lt\UpvoteBundle\Controller;

use Lt\UpvoteBundle\Model\VoteManager;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

class TestController extends Controller
{
    private $testContextType = [
        'testPost1' => [
            'show_upvote' => true,
            'show_downvote' => true,
            'allow_anonymous_upvote' => true,
            'allow_anonymous_downvote' => true,
        ],
        'testPost2' => [
            'show_upvote' => true,
            'show_downvote' => false,
            'allow_anonymous_upvote' => true,
            'allow_anonymous_downvote' => true,
        ],
        'testPost3' => [
            'show_upvote' => true,
            'show_downvote' => true,
            'allow_anonymous_upvote' => true,
            'allow_anonymous_downvote' => false,
        ],
        'testComment' => [
            'show_upvote' => true,
            'show_downvote' => true,
            'allow_anonymous_upvote' => true,
            'allow_anonymous_downvote' => true,
        ],
    ];

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

        $this->voteManager->setTypes($this->testContextType);
        return $this->render('LtUpvoteBundle:Default:test.html.twig');
    }
}