<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/frontend', name: 'frontend')]
class FrontendController extends AbstractController
{
    #[Route('/outings', name: '_outings')]
    public function outings(): Response
    {
        return $this->render('frontend/outings.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }

    #[Route('/profile', name: '_profile')]
    public function profile(): Response
    {
        return $this->render('frontend/profile.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }

    #[Route('/login', name: '_login')]
    public function login(): Response
    {
        return $this->render('frontend/login.html.twig', [
            'controller_name' => 'FrontendController',
        ]);
    }
}
