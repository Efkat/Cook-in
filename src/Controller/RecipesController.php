<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentRecipeType;
use App\Form\RecipeType;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
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
    public function recipeView(ManagerRegistry $managerRegistry, String $slug, Request $request): Response
    {
        $recipeRepo = new RecipeRepository($managerRegistry);
        $recipe = $recipeRepo->findOneBy(['Slug' => $slug]);

        $commentRepo = new CommentRepository($managerRegistry);
        $comments = $commentRepo->findBy(['Recipe' => $recipe]);

        if($this->getUser()){
            $comment = new Comment();
            $commentForm = $this->createForm(CommentRecipeType::class, $comment);
            $commentFormView = $commentForm->createView();

            $manager = $managerRegistry->getManager();

            $commentForm->handleRequest($request);
            if($commentForm->isSubmitted() && $commentForm->isValid()){
                $comment = $commentForm->getData();
                $comment->setAuthor($this->getUser())
                    ->setRecipe($recipe);

                $manager->persist($comment);
                $manager->flush();

                return $this->redirectToRoute("app_recipe_view", ["slug"=> $slug]);
            }
        }else{
            $commentFormView = null;
        }

        if($recipe){
            return $this->render("recipes/viewRecipe.html.twig", [
                "title" => $recipe->getTitle(),
                "recipe" => $recipe,
                "comments" => $comments,
                "form" => $commentFormView
            ]);
        }else{
            return $this->redirectToRoute('app_index');
        }
    }

    #[Route('/recipes/create', name: "app_recipe_create")]
    public function new(Request $request): Response
    {
        $recipeForm = $this->createForm(RecipeType::class);

        $recipeForm->handleRequest($request);
        if($recipeForm->isSubmitted() && $recipeForm->isValid()){
            $recipe = $recipeForm->getData();
            dd($request);
        }

        return $this->render('recipes/form.html.twig',[
            'title' => "Création d'une recette",
            "form" => $recipeForm->createView()
        ]);
    }
}
