<?php

namespace App\Tests;

use App\Entity\Outing;
use App\Entity\Registration;
use App\Entity\User;
use PHPUnit\Framework\TestCase;

class RegistrationTest extends TestCase
{
    public function testRegistrationDate()
    {
        $registration = new Registration();
        $registrationDate = new \DateTime('2024-01-30 15:00:00');
        $registration->setRegistrationDate($registrationDate);

        $this->assertEquals($registrationDate, $registration->getRegistrationDate());
    }

    public function testOuting()
    {
        $registration = new Registration();
        $outing = new Outing();
        $registration->setOuting($outing);

        $this->assertSame($outing, $registration->getOuting());
    }

    public function testParticipant()
    {
        $registration = new Registration();
        $participant = new User();
        $registration->setParticipant($participant);

        $this->assertSame($participant, $registration->getParticipant());
    }

    public function testEquality()
    {
        $registration1 = new Registration();
        $registration1->setRegistrationDate(new \DateTime('2024-01-30 15:00:00'));

        $registration2 = new Registration();
        $registration2->setRegistrationDate(new \DateTime('2024-01-30 15:00:00'));

        $this->assertEquals($registration1, $registration2);
    }
}
