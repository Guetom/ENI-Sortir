<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OutingControllerTest extends WebTestCase
{
    /*
    //#region Methods
    public function loginAs(string $email): \Symfony\Bundle\FrameworkBundle\KernelBrowser
    {
        $client = static::createClient();

        $userRepository = static::getContainer()->get(UserRepository::class);

        $testUser = $userRepository->findOneByEmail($email);

        $client->loginUser($testUser);

        $client->request('POST', '/login');

        return $client;
    }
    //#endregion

    public function testHomePage()
    {
        $client = static::createClient();

        $client->request('GET', '/');

        $statusCode = $client->getResponse()->getStatusCode();

        $this->assertEquals(200, $statusCode);

        $crawler = $client->request('GET', '/');
        $pageTitle = $crawler->filter('head > title')->text();

        $this->assertEquals("Hello HomeController!", $pageTitle);
    }

    public function testLoginAsAdmin()
    {
        $statusCode = $this->loginAs('admin@test.fr')->getResponse()->getStatusCode();

        // Vérifie que l'ont est bien rediriger (code 302)
        $this->assertEquals(302, $statusCode);
    }

    public function testIndex()
    {
        $client = $this->loginAs('admin@test.fr');

        $client->request('GET', '/outing/');

        // Vérifie que la réponse est réussie (code 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    */
}