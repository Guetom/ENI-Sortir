<?php

namespace App\Controller;

use App\Form\CreateOutingType;
use App\Form\SearchOutingType;
use App\Model\SearchOuting;
use App\Repository\OutingRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/sortie', name: 'outing_')]
class OutingController extends AbstractController
{
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(Request $request, OutingRepository $outingRepository): Response
    {
        $searchData = new SearchOuting();
        $form = $this->createForm(SearchOutingType::class, $searchData);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $outings = $outingRepository->findSearch($searchData, $this->getUser());
        } else {
            $outings = $outingRepository->findAll();
        }

        return $this->render('outing/index.html.twig', [
            'formSearch' => $form->createView(),
            'outings' => $outings,
        ]);
    }

    #[Route('/create', name: 'create', methods: ['GET'])]
    public function create(Request $request, OutingRepository $outingRepository): Response
    {
        $form = $this->createForm(CreateOutingType::class);

        return $this->render('outing/create.html.twig', [
            'formSearch' => $form->createView(),
            'outings' => $outings,
        ]);
    }
}
