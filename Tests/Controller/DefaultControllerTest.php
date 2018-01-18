<?php

namespace Lt\UpvoteBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/lt-upvote/blog/15/upvote/');

        $this->assertContains('upvoted', $client->getResponse()->getContent());
    }
}
