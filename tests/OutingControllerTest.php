<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OutingControllerTest extends WebTestCase
{
    public function loginAs(string $email, string $password): \Symfony\Bundle\FrameworkBundle\KernelBrowser
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/login');

        // Récupération du jeton CSRF du formulaire
        $csrfToken = $crawler->filter('input[name="_csrf_token"]')->attr('value');

        // Requête HTTP POST, soumition du formulaire de login
        $client->request('POST', '/login', [
            '_csrf_token' => $csrfToken,
            'email' => $email,
            'password' => $password,
        ]);

        return $client;
    }

    public function testLoginAsAdmin()
    {
        // Expected response: redirection (code 302)
        $this->assertEquals(302, $this->loginAs('admin@test.fr','password')->getResponse()->getStatusCode());
    }

    public function testIndex()
    {
        $client = static::createClient();

        // Effectue une requête HTTP GET sur l'URL /outing/
        $client->request('GET', '/outing/');

        // Vérifie que la réponse est réussie (code 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vous pouvez également vérifier le contenu de la réponse, par exemple, la présence d'un élément spécifique.
        // $this->assertSelectorTextContains('h1', 'Liste des sorties');
    }

    public function testCreate()
    {
        $client = static::createClient();

        // Effectue une requête HTTP GET sur l'URL /outing/create
        $client->request('GET', '/outing/create');

        // Vérifie que la réponse est réussie (code 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Vous pouvez également vérifier le contenu de la réponse, par exemple, la présence d'un élément spécifique dans le formulaire.
        // $this->assertSelectorExists('form button[type="submit"]');
    }

    public function testRegister()
    {
        // Étant donné que la méthode register nécessite une session utilisateur connectée,
        // vous devrez gérer l'authentification dans le test.
        $client = static::createClient([], [
            'PHP_AUTH_USER' => 'votre_nom_utilisateur',
            'PHP_AUTH_PW'   => 'votre_mot_de_passe',
        ]);

        // Effectue une requête HTTP GET sur l'URL /outing/registration/{id}
        $client->request('GET', '/outing/registration/{id}');

        // Vérifie que la réponse est réussie (code 200)
        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
