<?php

namespace App\Tests;

use App\Entity\City;
use App\Entity\Place;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class CityTest extends TestCase
{
    public function testSetName()
    {
        $city = new City();
        $city->setName('Paris');

        $this->assertEquals('Paris', $city->getName());
    }

    public function testSetPostcode()
    {
        $city = new City();
        $city->setPostcode('75001');

        $this->assertEquals('75001', $city->getPostcode());
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

    public function testToString()
    {
        $city = new City();
        $city->setName('Paris');

        $this->assertEquals('Paris', (string)$city);
    }

    public function testConstructWithPlaces()
    {
        $place1 = new Place();
        $place2 = new Place();

        $city = new City();
        $city->setName('Paris');
        $city->setPostcode('75001');
        $city->addPlace($place1);
        $city->addPlace($place2);

        $this->assertCount(2, $city->getPlaces());
    }

    public function testOverall()
    {
        $city = new City();

        $this->assertNull($city->getId());
        $this->assertNull($city->getName());
        $this->assertNull($city->getPostcode());
        $this->assertCount(0, $city->getPlaces());

        $city->setName('Paris');
        $city->setPostcode('75001');

        $place1 = new Place();

        $city->addPlace($place1);

        $this->assertEquals('Paris',$city->getName());
        $this->assertEquals('75001',$city->getPostcode());
        $this->assertCount(1, $city->getPlaces());
        $this->assertTrue($city->getPlaces()->contains($place1));

        $city->removePlace($place1);

        $this->assertCount(0, $city->getPlaces());
        $this->assertFalse($city->getPlaces()->contains($place1));

        $city->addPlace($place1);

        $place2 = new Place();

        $city->addPlace($place2);

        $this->assertCount(2, $city->getPlaces());
        $this->assertTrue($city->getPlaces()->contains($place1));
        $this->assertTrue($city->getPlaces()->contains($place2));

        $city->removePlace($place2);
        $this->assertCount(1, $city->getPlaces());
        $this->assertFalse($city->getPlaces()->contains($place2));
        $this->assertTrue($city->getPlaces()->contains($place1));

        $city->removePlace($place1);
        $this->assertCount(0, $city->getPlaces());
        $this->assertFalse($city->getPlaces()->contains($place1));
    }
}
