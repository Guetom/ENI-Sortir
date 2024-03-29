<?php

namespace App\Controller;

use App\Entity\Outing;
use App\Entity\Place;
use App\Entity\Registration;
use App\Entity\Status;
use App\Form\CreateOutingType;
use App\Form\SearchOutingType;
use App\Model\SearchOuting;
use App\Repository\OutingRepository;
use App\Repository\RegistrationRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

#[Route('/outing', name: 'outing_')]
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
            $searchDataNull = new SearchOuting();
            $outings = $outingRepository->findSearch($searchDataNull, null);
        }

        return $this->render('outing/index.html.twig', [
            'formSearch' => $form->createView(),
            'outings' => $outings,
        ]);
    }

    #[Route('/create', name: 'create')]
    public function create(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CreateOutingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $outing = $form->getData();
            $violations = $validator->validate($outing);

            if (count($violations) > 0) {
                foreach ($violations as $violation) {
                    $this->addFlash('error', $violation->getMessage());
                }
            } elseif ($form->isValid()) {
                $outing->setOrganizer($this->getUser());

                if ($form->get('poster')->getData()) {
                    $poster = $form->get('poster')->getData();
                    $newFilename = uniqid() . '.' . $poster->guessExtension();
                    $poster->move(
                        'uploads/',
                        $newFilename
                    );

                    $outing->setPoster($newFilename);
                }

                $placeData = $form->get('place')->getData();

                $place = new Place();
                $place->setName($placeData->getName());
                $place->setAddress($placeData->getAddress());
                $place->setLatitude($placeData->getLatitude());
                $place->setLongitude($placeData->getLongitude());
                $place->setCity($placeData->getCity());

                $outing->setPlace($place);

                $status = $entityManager->getRepository(Status::class)->findOneBy(['label' => 'Ouverte']);

                if ($status == null) {
                    $status = new Status();
                    $status->setLabel('Ouverte');
                    $entityManager->persist($status);
                }

                $outing->setStatus($status);

                $entityManager->persist($place);
                $entityManager->persist($outing);
                $entityManager->flush();

                $this->addFlash('success', 'Vous avez créé une nouvelle sortie');
                return $this->redirectToRoute('outing_preview', ['id' => $outing->getId()]);
            }

        }

        return $this->render('outing/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/registration/{id}', name: 'register', methods: ['GET'])]
    public function register(
        Request                $request,
        OutingRepository       $outingRepository,
        RegistrationRepository $registrationRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        $userConnected = $this->getUser();

        //Check si l'utilisateur est connecté
        if (!$userConnected) {
            $this->addFlash('danger', 'Vous devez être connecté pour vous inscrire à une sortie');
            return $this->redirectToRoute('outing_index');
        }

        //Check si l'utilisateur est déjà inscrit
        $outing = $outingRepository->find($request->get('id'));
        $registration = $registrationRepository->findOneBy([
            'outing' => $outing,
            'participant' => $userConnected
        ]);
        if ($registration) {
            $this->addFlash('danger', 'Vous êtes déjà inscrit à cette sortie');
            return $this->redirectToRoute('outing_index');
        }

        //Check si la sortie est déjà passée et si l'inscription est encore possible et si il reste de la place
        if (!$outing->canRegister()) {
            $this->addFlash('danger', 'Vous ne pouvez plus vous inscrire à cette sortie');
            return $this->redirectToRoute('outing_index');
        }

        //Inscription
        $registration = new Registration();
        $registration->setParticipant($this->getUser());
        $registration->setOuting($outing);
        $registration->setRegistrationDate(new \DateTime('now'));
        $entityManager->persist($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes inscrit à la sortie');
        return $this->redirectToRoute('outing_index');
    }

    #[Route('/unregistration/{id}', name: 'unregister', methods: ['GET'])]
    public function unregister(
        Request                $request,
        OutingRepository       $outingRepository,
        RegistrationRepository $registrationRepository,
        EntityManagerInterface $entityManager
    ): Response
    {
        //Recupération de l'utilisateur connecté
        $userConnected = $this->getUser();

        //Check si l'utilisateur est connecté
        if (!$userConnected) {
            $this->addFlash('danger', 'Vous devez être connecté pour vous désinscrire d\'une sortie');
            return $this->redirectToRoute('outing_index');
        }

        //Check si l'utilisateur est déjà inscrit
        $outing = $outingRepository->find($request->get('id'));
        $registration = $registrationRepository->findOneBy([
            'outing' => $outing,
            'participant' => $userConnected
        ]);
        if (!$registration) {
            $this->addFlash('danger', 'Vous n\'êtes pas inscrit à cette sortie');
            return $this->redirectToRoute('outing_index');
        }

        //Désinscription
        $entityManager->remove($registration);
        $entityManager->flush();

        $this->addFlash('success', 'Vous êtes désinscrit de la sortie');
        return $this->redirectToRoute('outing_index');
    }

    #[Route('/{id}', name: 'preview')]
    public function preview(Request $request, OutingRepository $outingRepository): Response
    {
        $outing = $outingRepository->find($request->get('id'));

        //Vérification si la sortie existe
        if (!$outing) {
            throw $this->createNotFoundException('La sortie n\'existe pas');
        }

        //Recupération de la météo du jour de la sortie (uniquement si la sortie n'est pas passée et si la date est dans les 10 prochains jours)
        if ($outing->getStartDate() > new \DateTime('now') && $outing->getStartDate() < new \DateTime('+10 days')) {
            try {
                $url = $_ENV['METEO_CONCEPT_URL'] . 'location/cities?token=' . $_ENV['METEO_CONCEPT_API_KEY'] . '&search=' . $outing->getPlace()->getCity()->getPostcode();
                $inseeCode = json_decode(file_get_contents($url), true)['cities'][0]['insee'];
                $url = $_ENV['METEO_CONCEPT_URL'] . 'forecast/daily?token=' . $_ENV['METEO_CONCEPT_API_KEY'] . '&insee=' . $inseeCode;
                $nbDaysAfterToday = $outing->getStartDate()->diff(new \DateTime('now'))->days;
                if (json_decode(file_get_contents($url), true)['city']['insee'] == $inseeCode) {
                    $weather = json_decode(file_get_contents($url), true)['forecast'][$nbDaysAfterToday];
                } else {
                    $weather = null;
                }
            } catch (\Exception $e) {
                $weather = null;
            }
        } else {
            $weather = null;
        }

        //Export json pour $weather
//        file_put_contents('weather.json', json_encode($weather));

        //Récupération de la météo depuis le fichier json
//        $weather = json_decode(file_get_contents('weather.json'), true);

        return $this->render('outing/preview.html.twig', [
            'outing' => $outing,
            'weather' => $weather,
        ]);
    }

    #[Route('/{id}/edit', name: 'edit')]
    public function edit(Request $request, EntityManagerInterface $entityManager, ValidatorInterface $validator): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $outing = $entityManager->getRepository(Outing::class)->findOneBy(['id' => $request->get('id')]);

        // Vérifier si l'utilisateur actuel est l'organisateur de la sortie
        if ($outing->getOrganizer() !== $this->getUser()) {
            throw $this->createAccessDeniedException('Vous n\'êtes pas autorisé à modifier cette sortie.');
        }

        $form = $this->createForm(CreateOutingType::class, $outing);
        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            $outing = $form->getData();
            $violations = $validator->validate($outing);

            if (count($violations) > 0) {
                foreach ($violations as $violation) {
                    $this->addFlash('error', $violation->getMessage());
                }
            } elseif($form->isValid()){
                if ($form->get('poster')->getData()) {
                    $poster = $form->get('poster')->getData();
                    $newFilename = uniqid() . '.' . $poster->guessExtension();
                    $poster->move('uploads/', $newFilename);

                    $outing->setPoster($newFilename);
                }

                $entityManager->flush();

                $this->addFlash('success', 'La sortie a été modifiée avec succès.');
                return $this->redirectToRoute('outing_preview', ['id' => $outing->getId()]);
            }


        }

        return $this->render('outing/create.html.twig', [
            'form' => $form->createView(),
            'outing' => $outing,
        ]);
    }

    #[Route('/cancel/{id}', name: 'cancel')]
    public function cancel(Request $request, EntityManagerInterface $entityManager, ?Outing $outing): Response
    {
        if($outing){
            $cancelStatus = $entityManager->getRepository(Status::class)->findOneBy(['label' => 'Annulée']);

            if($cancelStatus){
                $outing->setStatus($cancelStatus);
                $entityManager->persist($outing);
                $entityManager->flush();
                $this->addFlash('success', 'La sortie a été annulée.');
            } else {
                $this->addFlash('error', 'Impossible d\'annuler la sortie. Veuillez contacter un administrateur en lui indiquant que le statut "Annulé" n\'existe pas.');
            }
        }
        return $this->redirectToRoute('outing_preview', ['id' => $outing->getId()]);
    }
}
