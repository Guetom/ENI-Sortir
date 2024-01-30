<?php

namespace App\Controller\Admin;

use App\Entity\City;
use App\Entity\Outing;
use App\Entity\Site;
use App\Entity\User;
use App\Form\ImportUserType;
use App\Repository\CityRepository;
use App\Repository\OutingRepository;
use App\Repository\SiteRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    private UserPasswordHasherInterface $passwordHashed;
    private EntityManagerInterface $em;
    const DEFAULT_PASSWORD = 'Pa$$w0rd';

    public function __construct(
        private UserRepository                       $userRepository,
        private OutingRepository                     $outingRepository,
        private SiteRepository                       $siteRepository,
        private CityRepository                       $cityRepository,
        private readonly EntityManagerInterface      $entityManager,
        private readonly UserPasswordHasherInterface $Harsher
    )
    {
        $this->em = $this->entityManager;
        $this->passwordHashed = $this->Harsher;
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig',
            [
                'numberUserActive' => $this->userRepository->count(['disable' => false]),
                'numberUserDisable' => $this->userRepository->count(['disable' => true]),
                'numberOutingNotStarted' => $this->outingRepository->countAfterDate(new \DateTime()),
                'numberOutingFinished' => $this->outingRepository->countBeforeDate(new \DateTime()),
                'numberRegistered' => $this->userRepository->count([])
            ]);
    }

    #[Route('/admin/import', name: 'admin-import-user')]
    public function importUser(Request $request): Response
    {
        $form = $this->createForm(ImportUserType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file = $form->get('csvFile')->getData();
            $totalNumber = 0;
            $recordNumber = 0;

            //Ouverture du fichier
            if (($handle = fopen($file, "r")) !== FALSE) {
                //Lecture de la première ligne
                $data = fgetcsv($handle, 1000, ";");
                //Lecture des lignes suivantes
                while (($data = fgetcsv($handle, 1000, ";")) !== FALSE) {
                    //Création d'un utilisateur
                    $totalNumber++;
                    $user = new User();
                    $user
                        ->setRoles(['ROLE_USER'])
                        ->setFirstname($data[0])
                        ->setLastname($data[1])
                        ->setPseudo($data[2])
                        ->setEmail($data[3])
                        ->setDisable(false)
                        ->setPassword($this->passwordHashed->hashPassword($user, $this::DEFAULT_PASSWORD));
                    if ($data[4] != null) {
                        $user->setPhone($data[4]);
                    }
                    if (!$this->userRepository->findOneBy((array)['email' => $data[3]]) && !$this->userRepository->findOneBy((array)['pseudo' => $data[2]])) {
                        if ($this->siteRepository->findOneBy((array)['name' => $data[5]])) {
                            $user->setSite($this->siteRepository->findOneBy((array)['name' => $data[5]]));
                            $this->em->persist($user);
                            $recordNumber++;
                        }
                    }
                }
                fclose($handle);
                $this->em->flush();
            }

            if ($recordNumber == 0) {
                $this->addFlash('danger', 'Aucun utilisateur n\'a été importé. Vérifier les données du fichier.');
            } else {
                $this->addFlash('success', 'Importation réussie de ' . $recordNumber . ' utilisateurs sur ' . $totalNumber . ' lignes.');
            }
            return $this->redirectToRoute('admin');
        }

        return $this->render('admin/importUser.html.twig', [
            'requestForm' => $form->createView(),
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setLocales(['fr'])
            ->setFaviconPath('/favicon.ico')
            ->setTitle('ENI Sortir');
    }

    public function configureMenuItems(): iterable
    {
        $numberUsers = $this->userRepository->count([]);
        $numberOutings = $this->outingRepository->count([]);
        $numberSites = $this->siteRepository->count([]);
        $numberCities = $this->cityRepository->count([]);

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard'),

            MenuItem::section('Administration'),
            MenuItem::linkToCrud('Utilisateurs', 'fas fa-users', User::class)
                ->setBadge($numberUsers, 'primary'),
            MenuItem::linkToRoute('Import CSV', 'fas fa-file-import', 'admin-import-user'),
            MenuItem::linkToCrud('Sorties', 'fa-solid fa-person-hiking', Outing::class)
                ->setBadge($numberOutings, 'primary'),
            MenuItem::linkToCrud('Sites', 'fas fa-map-location-dot', Site::class)
                ->setBadge($numberSites, 'primary'),
            MenuItem::linkToCrud('Villes', 'fas fa-map-pin', City::class)
                ->setBadge($numberCities, 'primary'),
            MenuItem::section(''),
            MenuItem::linkToRoute('Retour au site', 'fas fa-home', 'app_home'),
            MenuItem::section(''),
            MenuItem::linkToLogout('Déconnexion', 'fa-solid fa-right-from-bracket')
        ];
    }
}
