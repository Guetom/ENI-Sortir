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

    #[Route('/profile/readonly', name: '_profile_readonly')]
    public function loginReadOnly(): Response
    {
        return $this->render('frontend/profile-readonly.html.twig', [
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

    // Pour la route, nom provisoire, je sais pas quoi mettre
    #[Route('/outings/show', name: '_outings/show')]
    public function createOuting(): Response
    {
        return $this->render('frontend/outings-show.html.twig', [
            'controller_name' => 'FrontendController',
            'consultMode' => 'create' // là c'est pour tester mais tu peux faire 'create', 'modify' ou 'consult'
        ]);
    }
}
