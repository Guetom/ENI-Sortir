<?php

namespace App\Tests;

use App\Entity\Place;
use App\Entity\City;
use App\Entity\Outing;
use PHPUnit\Framework\TestCase;

class PlaceTest extends TestCase
{
    public function testName()
    {
        $place = New Place();
        $place->setName('Tour Eiffel');

        $this->assertEquals('Tour Eiffel', $place->getName());
    }

    public function testAddress()
    {
        $place = New Place();
        $place->setAddress('Champ de Mars, 5 Av. Anatole France, 75007 Paris');

        $this->assertEquals('Champ de Mars, 5 Av. Anatole France, 75007 Paris', $place->getAddress());
    }

    public function testLatitude()
    {
        $place = new Place();
        $place->setLatitude(48.85857127378631);

        $this->assertEquals(48.85857127378631, $place->getLatitude());
    }

    public function testLongitude()
    {
        $place = new Place();
        $place->setLongitude(2.2944598423291853);

        $this->assertEquals(2.2944598423291853, $place->getLongitude());
    }

    public function testCity()
    {
        $place = new Place();
        $city = new City();
        $place->setCity($city);

        $this->assertSame($city, $place->getCity());
    }

    public function testOuting()
    {
        $place = new Place();
        $outing = new Outing();
        $place->addOuting($outing);

        $this->assertNotEmpty($place->getOutings());
        $this->assertTrue($place->getOutings()->contains($outing));
    }

    public function testRemoveOuting()
    {
        $place = new Place();
        $outing = new Outing();
        $place->addOuting($outing);
        $place->removeOuting($outing);

        $this->assertEmpty($place->getOutings());
    }

    public function testToString()
    {
        $place = new Place();
        $place->setName('Tour Eiffel');

        $this->assertEquals('Tour Eiffel', (string)$place);
    }

    public function testEquality()
    {
        $place1 = new Place();
        $place1->setName('Tour Eiffel');

        $place2 = new Place();
        $place2->setName('Tour Eiffel');

        $this->assertEquals($place1, $place2);
    }
}
