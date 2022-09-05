<?php

namespace App\Controller\Admin;

use App\Entity\Tag;
use App\Entity\User;
use App\Entity\Recipe;
use App\Entity\Comment;
use Doctrine\ORM\EntityManager;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Repository\RecipeRepository;
use App\Repository\CommentRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

class DashboardController extends AbstractDashboardController
{

    protected UserRepository $userRepository;
    protected RecipeRepository $recipeRepository;
    protected CommentRepository $commentRepository;
    protected TagRepository $tagRepository;

    public function __construct(UserRepository $userRepo, RecipeRepository $recipeRepo, CommentRepository $commentRepo, TagRepository $tagRepo)
    {
        $this->userRepository = $userRepo;
        $this->recipeRepository = $recipeRepo;
        $this->commentRepository = $commentRepo;
        $this->tagRepository = $tagRepo;

    }

    /**
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws \Doctrine\ORM\NoResultException
     */
    #[Route('/admin', name: 'admin')]
    public function index(): Response
    {
        return $this->render('admin/dashboard.html.twig', [
            "users" => $this->userRepository->getNumbersOfUsers(),
            "recipes" => $this->recipeRepository->getNumbersOfRecipes(),
            "tags" => $this->tagRepository->getNumbersOfTags(),
            "comments" => $this->commentRepository->getNumbersOfComments()
        ]);
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
