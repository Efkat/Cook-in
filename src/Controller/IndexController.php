<?php

namespace App\Controller;

use App\Form\SearchRecipeType;

use App\Repository\RecipeRepository;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    #[Route('/', name: 'app_index')]
    public function index(ManagerRegistry $registry, Request $request): Response
    {
        $recipeRepo = new RecipeRepository($registry);

        $form = $this->createForm(SearchRecipeType::class);

        if($form->handleRequest($request) && $form->isSubmitted() && $form->isValid() ){
            $criteria = $form->getData();
            $recipes = $recipeRepo->findAllLike($criteria["name"]);
            return $this->render("search/index.html.twig", [
                "title" => $criteria["name"],
                "recipes" => $recipes
            ]);
        }

        return $this->render('index/index.html.twig', [
            "title" => "Acceuil",
            "form" => $form->createView()
        ]);
    }
}
