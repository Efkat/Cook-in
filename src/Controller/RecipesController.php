<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class RecipesController extends AbstractController
{
    #[Route('/recipes', name: 'app_recipes')]
    public function index(ManagerRegistry $managerRegistry): Response
    {
        $recipeRepo = new RecipeRepository($managerRegistry);

        $recipes = $recipeRepo->findAll();

        return $this->render('recipes/index.html.twig', [
            'title' => 'Recettes',
            'recipes' => $recipes
        ]);
    }

    #[Route('/recipe/{slug}', name: 'app_recipe_view')]
    public function recipeView(ManagerRegistry $managerRegistry, String $slug): Response
    {
        $recipeRepo = new RecipeRepository($managerRegistry);
        $recipe = $recipeRepo->findOneBy(['Slug' => $slug]);

        $commentRepo = new CommentRepository($managerRegistry);
        $comments = $commentRepo->findBy(['Recipe' => $recipe]);

        if($recipe){

            return $this->render("recipes/viewRecipe.html.twig", [
                "title" => $recipe->getTitle(),
                "recipe" => $recipe,
                "comments" => $comments
            ]);
        }else{
            return $this->redirectToRoute('app_index');
        }
    }
}
