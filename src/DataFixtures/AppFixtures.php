<?php

namespace App\DataFixtures;

use App\Entity\City;
use App\Entity\Outing;
use App\Entity\Place;
use App\Entity\Registration;
use App\Entity\Site;
use App\Entity\Status;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
    private UserPasswordHasherInterface $passwordHashed;
    private const DEFAULT_PASSWORD = 'password';

    public function __construct(private readonly UserPasswordHasherInterface $Harsher)
    {
        $this->passwordHashed = $this->Harsher;
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $listSite = new ArrayCollection();
        $listPlace = new ArrayCollection();
        $listUser = new ArrayCollection();
        $listStatus = new ArrayCollection();
        $listeOuting = new ArrayCollection();

        //Site (écoles)
        $site1 = new Site();
        $site1
            ->setName('Campus ENI Nantes');
        $manager->persist($site1);
        $listSite->add($site1);

        $site2 = new Site();
        $site2
            ->setName('Campus ENI Rennes');
        $manager->persist($site2);
        $listSite->add($site2);

        //Admin
        $admin = new User();
        $admin
            ->setPseudo('admin')
            ->setRoles(['ROLE_ADMIN'])
            ->setLastname('ADMIN')
            ->setFirstname('Admin')
            ->setEmail('admin@test.fr')
            ->setPhone('0600000000')
            ->setSite($site1)
            ->setDisable(false)
            ->setPassword($this->passwordHashed->hashPassword($admin, self::DEFAULT_PASSWORD));
        $manager->persist($admin);

        //Utilisateur simple
        for ($i = 0; $i < 50; $i++) {
            $user = new User();
            $user
                ->setPseudo($faker->userName)
                ->setRoles(['ROLE_USER'])
                ->setLastname($faker->lastName)
                ->setFirstname($faker->firstName)
                ->setEmail($faker->email)
                ->setPhone($faker->phoneNumber)
                ->setSite($listSite->get(rand(0, $listSite->count() - 1)))
                ->setDisable(false)
                ->setPassword($this->passwordHashed->hashPassword($user, self::DEFAULT_PASSWORD));
            $listUser->add($user);
            $manager->persist($user);
        }

        $city1 = new City();
        $city1
            ->setName('Nantes')
            ->setPostcode('44000');
        $manager->persist($city1);

        $city2 = new City();
        $city2
            ->setName('Rennes')
            ->setPostcode('35000');
        $manager->persist($city2);

        $city3 = new City();
        $city3
            ->setName('Paris')
            ->setPostcode('75000');
        $manager->persist($city3);

        $place1 = new Place();
        $place1
            ->setName('Place 1')
            ->setAddress('1 rue de la place')
            ->setLatitude(47.2186371)
            ->setLongitude(-1.5541362)
            ->setCity($city1);
        $listPlace->add($place1);
        $manager->persist($place1);

        $place2 = new Place();
        $place2
            ->setName('Place 2')
            ->setAddress('2 rue de la place')
            ->setLatitude(47.2186371)
            ->setLongitude(-1.5541362)
            ->setCity($city2);
        $listPlace->add($place2);
        $manager->persist($place2);

        $place3 = new Place();
        $place3
            ->setName('Place 3')
            ->setAddress('3 rue de la place')
            ->setLatitude(47.2186371)
            ->setLongitude(-1.5541362)
            ->setCity($city3);
        $listPlace->add($place3);
        $manager->persist($place3);

        $place4 = new Place();
        $place4
            ->setName('Place 4')
            ->setAddress('4 rue de la place')
            ->setLatitude(47.2186371)
            ->setLongitude(-1.5541362)
            ->setCity($city1);
        $listPlace->add($place4);
        $manager->persist($place4);

        $place5 = new Place();
        $place5
            ->setName('Place 5')
            ->setAddress('5 rue de la place')
            ->setLatitude(47.2186371)
            ->setLongitude(-1.5541362)
            ->setCity($city2);
        $listPlace->add($place5);
        $manager->persist($place5);

        //Status
        $status1 = new Status();
        $status1
            ->setLabel('Ouverte');
        $manager->persist($status1);
        $listStatus->add($status1);

        $status2 = new Status();
        $status2
            ->setLabel('Bientôt ouverte');
        $manager->persist($status2);
        $listStatus->add($status2);

        $status3 = new Status();
        $status3
            ->setLabel('Clôturée');
        $manager->persist($status3);
        $listStatus->add($status3);

        $status4 = new Status();
        $status4
            ->setLabel('Activité en cours');
        $manager->persist($status4);
        $listStatus->add($status4);

        //Création des sorties
        for ($i = 0; $i < 50; $i++) {
            $outing = new Outing();
            $outing
                ->setTitle('Sortie ' . $i)
                ->setStartDate($faker->dateTimeBetween('-1 week', '+1 week'))
                ->setDescription($faker->text())
                ->setDuration(rand(1, 5))
                ->setClosingDate($faker->dateTimeBetween('-1 week', '+1 week'))
                ->setOrganizer($listUser->get(rand(0, $listUser->count() - 1)))
                ->setStatus($listStatus->get(rand(0, $listStatus->count() - 1)))
                ->setPlace($listPlace->get(rand(0, $listPlace->count() - 1)))
                ->setRegistrationsMax(rand(10, 25));
            $listeOuting->add($outing);
            $manager->persist($outing);
        }

        //Création des inscriptions
        for ($i = 0; $i < 150; $i++) {
            $registration = new Registration();
            $registration
                ->setRegistrationDate($faker->dateTimeBetween('-3 days', '+3 days'))
                ->setOuting($listeOuting->get(rand(0, $listeOuting->count() - 1)))
                ->setParticipant($listUser->get(rand(0, $listUser->count() - 1)));
            $manager->persist($registration);
        }

        $manager->flush();
    }
}
