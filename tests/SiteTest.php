<?php

namespace App\Tests;

use App\Entity\Site;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class SiteTest extends TestCase
{
    public function testName()
    {
        $site = new Site();
        $site->setName('Campus Faraday');
        $this->assertEquals('Campus Faraday', $site->getName());
    }

    public function testAddUser()
    {
        $site = new Site();
        $user = new User();
        $site->addUser($user);

        $this->assertNotEmpty($site->getUsers());
        $this->assertTrue($site->getUsers()->contains($user));
    }

    public function testRemoveUser()
    {
        $site = new Site();
        $user = new User();
        $site->addUser($user);
        $site->removeUser($user);

        $this->assertEmpty($site->getUsers());
    }

    public function testToString()
    {
        $site = new Site();
        $site->setName('Campus Faraday');

        $this->assertEquals('Campus Faraday', (string)$site);
    }
}
