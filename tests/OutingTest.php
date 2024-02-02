<?php

namespace App\Tests;

use App\Entity\Outing;
use App\Entity\Place;
use App\Entity\Registration;
use App\Entity\Status;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class OutingTest extends TestCase
{
    public function testTitle()
    {
        $outing = new Outing();
        $outing->setTitle('Picnic');

        $this->assertEquals('Picnic', $outing->getTitle());
    }

    public function testStartDate()
    {
        $outing = new Outing();
        $startDate = new \DateTime('1789-07-14 10:00:00');
        $outing->setStartDate($startDate);

        $this->assertEquals($startDate, $outing->getStartDate());
    }

    public function testDuration()
    {
        $outing = new Outing();
        $outing->setDuration(120);

        $this->assertEquals(120, $outing->getDuration());
    }

    public function testClosingDate()
    {
        $outing = new Outing();
        $closingDate = new \DateTime('1789-07-14 10:00:00');
        $outing->setClosingDate($closingDate);

        $this->assertEquals($closingDate, $outing->getClosingDate());
    }

    public function testDescription()
    {
        $outing = new Outing();
        $outing->setDescription('Un sacré picnic.');

        $this->assertEquals('Un sacré picnic.', $outing->getDescription());
    }

    public function testPoster()
    {
        $outing = new Outing();
        $outing->setPoster('poster_image.jpg');

        $this->assertEquals('poster_image.jpg', $outing->getPoster());
    }

    public function testOrganizer()
    {
        $outing = new Outing();
        $organizer = new User();
        $outing->setOrganizer($organizer);

        $this->assertSame($organizer, $outing->getOrganizer());
    }

    public function testStatus()
    {
        $outing = new Outing();
        $status = new Status();
        $status->setLabel('Archived');
        $outing->setStatus($status);

        $this->assertSame($status, $outing->getStatus());
    }

    public function testPlace()
    {
        $outing = new Outing();
        $place = new Place();
        $outing->setPlace($place);

        $this->assertEquals($place, $outing->getPlace());
    }

    public function testAddRegistration()
    {
        $outing = new Outing();
        $user = new User();
        $registration = new Registration();
        $registration->setParticipant($user);
        $outing->addRegistration($registration);

        $this->assertNotEmpty($outing->getRegistrations());
        $this->assertTrue($outing->getRegistrations()->contains($registration));
    }

    public function testRemoveRegistration()
    {
        $outing = new Outing();
        $user = new User();
        $registration = new Registration();
        $registration->setParticipant($user);
        $outing->addRegistration($registration);
        $outing->removeRegistration($registration);

        $this->assertEmpty($outing->getRegistrations());
    }

    public function testToString()
    {
        $outing = new Outing();
        $outing->setTitle('Picnic');

        $this->assertEquals('Picnic', (string)$outing);
    }

    public function testLatitude()
    {
        $outing = new Outing();
        $place = new Place();
        $place->setLatitude(48.85857127378631);
        $place->setLongitude(2.2944598423291853);
        $outing->setPlace($place);

        $this->assertEquals($place->getLatitude(), $outing->getLatitude());
    }

    public function testLongitude()
    {
        $outing = new Outing();
        $place = new Place();
        $place->setLatitude(48.85857127378631);
        $place->setLongitude(2.2944598423291853);
        $outing->setPlace($place);

        $this->assertEquals($place->getLongitude(), $outing->getLongitude());
    }

    public function testEquality()
    {
        $outing1 = new Outing();
        $outing1->setTitle('Picnic');
        $outing1->setStartDate(new \DateTime('1789-07-14 10:00:00'));

        $outing2 = new Outing();
        $outing2->setTitle('Picnic');
        $outing2->setStartDate(new \DateTime('1789-07-14 10:00:00'));

        $this->assertEquals($outing1, $outing2);
    }
}
