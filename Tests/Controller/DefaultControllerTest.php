<?php

namespace Lt\UpvoteBundle\Tests\Controller;

use Lt\UpvoteBundle\Tests\FunctionalTestCase;

class DefaultControllerTest extends FunctionalTestCase
{
    public function testIndex()
    {
        $client = static::createClient();
        $client->request('GET', '/lt-upvote/blog/15/upvote/');

        $this->assertContains('upvoted', $client->getResponse()->getContent());
    }
}
