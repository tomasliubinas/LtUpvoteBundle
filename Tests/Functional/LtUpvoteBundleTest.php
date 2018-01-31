<?php

namespace Lt\UpvoteBundle\Tests\Functional;

use Lt\UpvoteBundle\Tests\FunctionalTestCase;

class LtUpvoteBundleTest extends FunctionalTestCase
{
    public function testBlankUpvote()
    {
        $client = static::createClient();
        $client->request('GET', '/lt-upvote/blog/15/upvote/');

        $this->assertContains('upvoted', $client->getResponse()->getContent());
    }
}