<?php

namespace App\Controller\Admin;

use App\Entity\User;
use App\Repository\OutingRepository;
use App\Repository\UserRepository;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class DashboardController extends AbstractDashboardController
{
    public function __construct(
        private readonly UserRepository $userRepository,
        private readonly OutingRepository $outingRepository
    )
    {
    }

    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
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

        return [
            MenuItem::linkToDashboard('Dashboard', 'fa fa-dashboard'),

            MenuItem::section('Administration'),

            MenuItem::section(''),
            MenuItem::linkToLogout('Déconnexion', 'fa-solid fa-right-from-bracket')
        ];
    }
}
