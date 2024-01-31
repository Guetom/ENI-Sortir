<?php

namespace App\Tests;

use App\Entity\City;
use App\Entity\Place;
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
        $place->setLongitude(2.2944598423291853);

        $this->assertEquals($place->getLatitude(), $place->getLatitude());
    }

    public function testLongitude()
    {
        $place = new Place();
        $place->setLatitude(48.85857127378631);
        $place->setLongitude(2.2944598423291853);

        $this->assertEquals($place->getLongitude(), $place->getLongitude());
    }

    public function testCity()
    {
        $place = new Place();
        $city = new City();
        $place->setCity($city);

        $this->assertSame($city, $place->getCity());
    }

    public function testOutings()
    {

    }

    public function testRemoveOuting()
    {

    }

    public function testToString()
    {

    }

    public function testEquality()
    {

    }
}
