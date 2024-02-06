<?php

namespace App\Controller;

use App\Form\ProfileUserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/profile', name: 'user_')]
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
    public function edit(Request $request,UserRepository $userRepository, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        //Page accessible uniquement aux utilisateurs connectés
        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $form = $this->createForm(ProfileUserType::class, $this->getUser());
        $form->handleRequest($request);
        $user = $userRepository->find($this->getUser());

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setFirstname($form->get('firstname')->getData());
            $user->setLastname($form->get('lastname')->getData());
            $user->setPhone($form->get('phone')->getData());
            if ($form->get('profilePicture')->getData()) {
                //On récupère la photo de profil
                $profilePicture = $form->get('profilePicture')->getData();
                //On crée un nouveau nom de fichier
                $newFilename = uniqid() . '.' . $profilePicture->guessExtension();
                //On déplace la photo de profil dans le dossier uploads
                $profilePicture->move(
                    'uploads/',
                    $newFilename
                );
                //On met à jour la photo de profil de l'utilisateur
                $user->setProfilePicture($newFilename);
            }
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour avec succès');
            return $this->redirectToRoute('user_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $userRepository->find($this->getUser()),
        ]);
    }

    #[Route('/{pseudo}', name: 'show')]
    public function show(UserRepository $userRepository, string $pseudo): Response
    {
        $userFound = $userRepository->findOneBy(['pseudo' => $pseudo]);

        if (!$userFound) {
            throw $this->createNotFoundException('L\'utilisateur n\'existe pas');
        }

        return $this->render('user/index.html.twig', [
            'user' => $userFound,
        ]);
    }
}
