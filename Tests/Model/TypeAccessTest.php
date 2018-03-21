<?php

namespace Lt\UpvoteBundle\Tests\Model;

use Lt\UpvoteBundle\Model\TypeAccess;
use PHPUnit\Framework\TestCase;

class TypeAccessTest extends TestCase
{
    /**
     * @var TypeAccess
     */
    private $typeAccess;

    protected function setUp()
    {
        $this->typeAccess = new TypeAccess();
    }

    public function testIsTypeAvailable()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertTrue($this->typeAccess->isTypeAvailable('testType', $availableTypes));
    }

    public function testIsTypeNotAvailable()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertFalse($this->typeAccess->isTypeAvailable('testType2', $availableTypes));
    }

    public function testIsVisibleUpvote()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertTrue($this->typeAccess->isVisibleUpvote('testType', $availableTypes));
    }

    public function testIsNotVisibleUpvote()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = false;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertFalse($this->typeAccess->isVisibleUpvote('testType', $availableTypes));
    }

    public function testIsVisibleDownvote()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertTrue($this->typeAccess->isVisibleDownvote('testType', $availableTypes));
    }

    public function testIsNotVisibleDownvote()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = false;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertFalse($this->typeAccess->isVisibleDownvote('testType', $availableTypes));
    }

    public function testCanUpvoteAnonymous()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertTrue($this->typeAccess->canUpvoteAnonymous('testType', $availableTypes));
    }

    public function testCanNotUpvoteAnonymous()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = false;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertFalse($this->typeAccess->canUpvoteAnonymous('testType', $availableTypes));
    }

    public function testCanDownvoteAnonymous()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = true;

        $this->assertTrue($this->typeAccess->canDownvoteAnonymous('testType', $availableTypes));
    }
    public function testCanNotDownvoteAnonymous()
    {
        $availableTypes = [];
        $availableTypes['testType']['show_upvote'] = true;
        $availableTypes['testType']['show_downvote'] = true;
        $availableTypes['testType']['allow_anonymous_upvote'] = true;
        $availableTypes['testType']['allow_anonymous_downvote'] = false;

        $this->assertFalse($this->typeAccess->canDownvoteAnonymous('testType', $availableTypes));
    }
}
