<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipesController extends AbstractController
{
    #[Route('/recipes', name: 'app_recipes')]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $recipeRepo = $managerRegistry->getRepository(Recipe::class);

        $recipes = $recipeRepo->findAll();

        return $this->render('recipes/index.html.twig', [
            'title' => 'Recettes',
            'recipes' => $recipes
        ]);
    }
}
