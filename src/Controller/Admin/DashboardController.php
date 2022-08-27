<?php

namespace App\Controller\Admin;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Entity\Tag;
use App\Entity\User;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DashboardController extends AbstractDashboardController
{
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig');
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Cook\'in App');
    }

    public function configureMenuItems(): iterable
    {
            yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');

            yield MenuItem::section('Users');
            yield MenuItem::linkToCrud('Users', 'fa fa-user', User::class);
            yield MenuItem::linkToCrud('Comments', 'fa fa-comment', Comment::class);

            yield MenuItem::section('Recipes');
            yield MenuItem::linkToCrud("Recipes", "fa fa-book", Recipe::class );
            yield MenuItem::linkToCrud("Tags", "fa fa-tag", Tag::class);



    }
}
