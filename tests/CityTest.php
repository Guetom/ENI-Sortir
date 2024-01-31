<?php

namespace App\Tests;

use App\Entity\City;
use App\Entity\Place;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    public function testName()
    {
        $city = new City();
        $city->setName('Paris');

        $this->assertEquals('Paris', $city->getName());
    }

    public function testPostcode()
    {
        $city = new City();
        $city->setPostcode('75001');

        $this->assertEquals('75001', $city->getPostcode());
    }

    public function testAddPlace()
    {
        $city = new City();
        $place = new Place();
        $city->addPlace($place);

        $this->assertTrue($city->getPlaces()->contains($place));
    }

    public function testRemovePlace()
    {
        $city = new City();
        $place = new Place();
        $city->addPlace($place);
        $city->removePlace($place);

        $this->assertFalse($city->getPlaces()->contains($place));
    }

    public function testToString()
    {
        $city = new City();
        $city->setName('Paris');

        $this->assertEquals('Paris', (string)$city);
    }

    public function testEquality()
    {
        $city1 = new City();
        $city1->setName('Paris');
        $city1->setPostcode('75001');

        $city2 = new City();
        $city2->setName('Paris');
        $city2->setPostcode('75001');

        $this->assertEquals($city1, $city2);
    }

    public function testPlaces()
    {
        $city = new City();
        $place1 = new Place();
        $place2 = new Place();
        $place3 = new Place();
        $city->addPlace($place1);
        $city->addPlace($place2);
        $city->addPlace($place3);
        $city->removePlace($place2);

        $this->assertCount(2, $city->getPlaces());
        $this->assertTrue($city->getPlaces()->contains($place1));
        $this->assertFalse($city->getPlaces()->contains($place2));
        $this->assertTrue($city->getPlaces()->contains($place3));
    }
}
