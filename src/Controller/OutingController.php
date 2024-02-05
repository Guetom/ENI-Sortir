<?php

namespace App\Controller;

use App\Entity\Place;
use App\Entity\Registration;
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
    public function create(Request $request, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_USER');

        $form = $this->createForm(CreateOutingType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $outing = $form->getData();
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

            $entityManager->persist($place);
            $entityManager->persist($outing);
            $entityManager->flush();

            $this->addFlash('success', 'Vous avez créé une nouvelle sortie');
            return $this->redirectToRoute('outing_index');
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
            $url = $_ENV['METEO_CONCEPT_URL'] . 'location/cities?token=' . $_ENV['METEO_CONCEPT_API_KEY'] . '&search=' . $outing->getPlace()->getCity()->getPostcode();
            $inseeCode = json_decode(file_get_contents($url), true)['cities'][0]['insee'];
            $url = $_ENV['METEO_CONCEPT_URL'] . 'forecast/daily?token=' . $_ENV['METEO_CONCEPT_API_KEY'] . '&insee=' . $inseeCode;
            $nbDaysAfterToday = $outing->getStartDate()->diff(new \DateTime('now'))->days;
            if (json_decode(file_get_contents($url), true)['city']['insee'] == $inseeCode) {
                $weather = json_decode(file_get_contents($url), true)['forecast'][$nbDaysAfterToday];
            } else {
                $weather = null;
            }
        } else {
            $weather = null;
        }

        return $this->render('outing/preview.html.twig', [
            'outing' => $outing,
            'weather' => $weather,
        ]);
    }
}
