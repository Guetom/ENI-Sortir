<?php

namespace App\Tests;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class HomeControllerTest extends WebTestCase
{
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
    }

    public function testHomePageLogIn()
    {
        $client = $this->loginAs('admin@test.fr');

        $crawler = $client->request('GET', '/');

        $loginButton = $crawler->filterXPath("//body/nav//a[contains(@href, '/login')]");

        $profileButton = $crawler->filterXPath("//body/nav//a[contains(@href, '/profile/')]");

        $this->assertCount(0, $loginButton, 'Le bouton login est sur la page.');
        $this->assertCount(1, $profileButton, 'Le bouton profile n\'est pas sur la page.');
    }

    public function testHomePageLogOut()
    {
        $client = $this->loginAs('admin@test.fr');

        $client->request('GET', '/logout/');

        $crawler = $client->request('GET', '/');


        $loginButton = $crawler->filterXPath("//body/nav//a[contains(@href, '/login')]");

        $profileButton = $crawler->filterXPath("//body/nav//a[contains(@href, '/profile/')]");

        $this->assertCount(1, $loginButton, 'Le bouton login n\'est pas sur la page.');
        $this->assertCount(0, $profileButton, 'Le bouton profile est sur la page.');
    }
}
