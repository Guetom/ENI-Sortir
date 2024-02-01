<?php

namespace App\Tests;

use App\Entity\Outing;
use App\Entity\Registration;
use App\Entity\Site;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testPseudo()
    {
        $user = new User();
        $user->setPseudo('john_doe');

        $this->assertEquals('john_doe', $user->getPseudo());
    }

    public function testUserIdentifier()
    {
        $user = new User();
        $user->setEmail('john@example.com');

        $this->assertEquals('john@example.com', $user->getUserIdentifier());
    }

    public function testRoles()
    {
        $user = new User();
        $user->setRoles(['ROLE_ADMIN']);

        $this->assertEquals(['ROLE_ADMIN', 'ROLE_USER'], $user->getRoles());
    }

    public function testPassword()
    {
        $user = new User();
        $user->setPassword('hashed_password');

        $this->assertEquals('hashed_password', $user->getPassword());
    }

    public function testLastname()
    {
        $user = new User();
        $user->setLastname('Doe');

        $this->assertEquals('Doe', $user->getLastname());
    }

    public function testFirstname()
    {
        $user = new User();
        $user->setFirstname('John');

        $this->assertEquals('John', $user->getFirstname());
    }

    public function testPhone()
    {
        $user = new User();
        $user->setPhone('123456789');

        $this->assertEquals('123456789', $user->getPhone());
    }

    public function testEmail()
    {
        $user = new User();
        $user->setEmail('john@example.com');

        $this->assertEquals('john@example.com', $user->getEmail());
    }

    public function testDisable()
    {
        $user = new User();
        $user->setDisable(true);

        $this->assertTrue($user->isDisable());
    }

    public function testSite()
    {
        $user = new User();
        $site = new Site();
        $user->setSite($site);

        $this->assertEquals($site, $user->getSite());
    }

    public function testAddOuting()
    {
        $user = new User();
        $outing = new Outing();
        $user->addOuting($outing);

        $this->assertNotEmpty($user->getOutings());
        $this->assertTrue($user->getOutings()->contains($outing));
    }

    public function testRemoveOuting()
    {
        $status = new User();
        $outing = new Outing();
        $status->addOuting($outing);
        $status->removeOuting($outing);

        $this->assertEmpty($status->getOutings());
    }

    public function testAddRegistration()
    {
        $user = new User();
        $outing = new Outing();
        $registration = new Registration();
        $registration->setParticipant($user);
        $outing->addRegistration($registration);

        $this->assertNotEmpty($outing->getRegistrations());
        $this->assertTrue($outing->getRegistrations()->contains($registration));
    }

    public function testRemoveRegistration()
    {
        $user = new User();
        $outing = new Outing();
        $registration = new Registration();
        $registration->setParticipant($user);
        $outing->addRegistration($registration);
        $outing->removeRegistration($registration);

        $this->assertEmpty($outing->getRegistrations());
    }

    public function testFullName()
    {
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $this->assertEquals('John Doe', $user->getFullName());
    }

    public function testToString()
    {
        $user = new User();
        $user->setFirstname('John');
        $user->setLastname('Doe');

        $this->assertEquals('John Doe', (string)$user);
    }

    public function testEquality()
    {
        $user1 = new User();
        $user1->setPseudo('john_doe');
        $user1->setEmail('john@example.com');

        $user2 = new User();
        $user2->setPseudo('john_doe');
        $user2->setEmail('john@example.com');

        $this->assertEquals($user1, $user2);
    }
}
