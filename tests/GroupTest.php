<?php

namespace App\Tests;

use App\Entity\User;
use App\Entity\Group;
use PHPUnit\Framework\TestCase;

class GroupTest extends TestCase
{

    public function testGroupName()
    {
        $group = new Group();
        $group->setGroupName('Les amis pour la vie');

        $this->assertEquals('Les amis pour la vie', $group->getGroupName());
    }

    public function testCreatedBy()
    {
        $group = new Group();
        $user = new User();
        $group->setCreatedBy($user);

        $this->assertSame($user, $group->getCreatedBy());
    }

    public function testEmptyGetGuest()
    {
        $group = new Group();

        $this->assertEmpty($group->getGuests());
    }

    public function testAddGuest()
    {
        $group = new Group();
        $user = new User();
        $group->addGuest($user);

        $this->assertNotEmpty($group->getGuests());
        $this->assertContains($user, $group->getGuests());
    }

    public function testRemoveGuest()
    {
        $group = new Group();
        $user = new User();
        $group->addGuest($user);
        $group->removeGuest($user);

        $this->assertEmpty($group->getGuests());
        $this->assertNotContains($user, $group->getGuests());
    }
}
