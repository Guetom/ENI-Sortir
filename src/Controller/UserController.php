<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profil', name: 'user_')]
class UserController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(UserRepository $userRepository): Response
    {
        //Page accessible uniquement aux utilisateurs connectés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/index.html.twig', [
            'user' => $userRepository->find($this->getUser()),
        ]);
    }

    #[Route('/edit', name: 'edit')]
    public function edit(UserRepository $userRepository): Response
    {
        //Page accessible uniquement aux utilisateurs connectés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        return $this->render('user/edit.html.twig', [
            'user' => $userRepository->find($this->getUser()),
        ]);
    }

    #[Route('/{pseudo}', name: 'show')]
    public function show(UserRepository $userRepository, string $pseudo): Response
    {
        return $this->render('user/show.html.twig', [
            'user' => $userRepository->findOneBy(['pseudo' => $pseudo]),
        ]);
    }
}
