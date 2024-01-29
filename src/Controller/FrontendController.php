<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/frontend', name: 'frontend')]
class FrontendController extends AbstractController
{
    #[Route('/', name: 'frontend_homepage')]
    public function homepage(): Response
    {
        return $this->render('frontend/homepage.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }
}
