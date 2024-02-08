<?php

namespace App\Controller;

use App\Form\GroupType;
use App\Repository\GroupRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/group', name: 'group_')]
class GroupController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(GroupRepository $groupRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        return $this->render('group/index.html.twig', [
            'groups' => $groupRepository->findMyGroup($this->getUser()),
        ]);
    }

//    #[Route('/create', name: 'create')]
//    public function create(): Response
//    {
//        $this->denyAccessUnlessGranted('ROLE_USER');
//
//        $form = $this->createForm(GroupType::class);
//
//        return $this->render('group/create.html.twig', [
//            'form' => $form->createView(),
//        ]);
//    }
}
