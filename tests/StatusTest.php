<?php

namespace App\Tests;

use App\Entity\Status;
use App\Entity\Outing;
use PHPUnit\Framework\TestCase;

class StatusTest extends TestCase
{
    public function testLabel()
    {
        $status = new Status();
        $status->setLabel('Active');

        $this->assertEquals('Active', $status->getLabel());
    }

    public function testAddOuting()
    {
        $status = new Status();
        $outing = new Outing();
        $status->addOuting($outing);

        $this->assertNotEmpty($status->getOutings());
        $this->assertTrue($status->getOutings()->contains($outing));
    }

    public function testRemoveOuting()
    {
        $status = new Status();
        $outing = new Outing();
        $status->addOuting($outing);
        $status->removeOuting($outing);

        $this->assertEmpty($status->getOutings());
    }

    public function testToString()
    {
        $status = new Status();
        $status->setLabel('Active');

        $this->assertEquals('Active', (string)$status);
    }

    public function testEquality()
    {
        $status1 = new Status();
        $status1->setLabel('Active');

        $status2 = new Status();
        $status2->setLabel('Active');

        $this->assertEquals($status1, $status2);
    }
}
