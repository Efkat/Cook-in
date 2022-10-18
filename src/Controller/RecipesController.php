<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentRecipeType;
use App\Form\RecipeType;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\Persistence\ManagerRegistry;
use MongoDB\Driver\Manager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\AsciiSlugger;

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

    //TODO : Check if the same title isn't  in database.
    #[Route('/recipes/create', name: "app_recipe_create")]
    public function new(ManagerRegistry $managerRegistry ,Request $request): Response
    {
        $recipeForm = $this->createForm(RecipeType::class);
        $slugger = new AsciiSlugger();
        $recipe = new Recipe();
        $manager = $managerRegistry->getManager();

        $recipeForm->handleRequest($request);
        if($recipeForm->isSubmitted() && $recipeForm->isValid() ){
            if($this->getUser()){
                $formDatas = $recipeForm->getNormData();
                $recipePicture = $recipeForm->get('picture')->getData();

                $recipe->setTitle($formDatas['title'])
                    ->setContent($formDatas['content'])
                    ->setDifficulty($formDatas['Difficulty'])
                    ->setPreparationTime($formDatas['preparationTime'])
                    ->setCookingTime($formDatas['cookingTime'])
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setModifiedAt(new \DateTimeImmutable())
                    ->setSlug($slugger->slug($formDatas['title']))
                    ->setUser($this->getUser());
                foreach ($formDatas['Tags'] as $tag) {
                    $recipe->addTag($tag);
                }

                if($recipePicture){
                    $safeFileName = $slugger->slug($recipe->getTitle());
                    $newFilename = $safeFileName . '-' . uniqid() . '.' . $recipePicture->guessExtension();

                    try{
                        $recipePicture->move(
                            $this->getParameter('picture-directory'),
                            $newFilename
                        );
                    }catch(FileException $e){
                        //TODO : Handle exception
                    }
                    $recipe->setPictureFileName($newFilename);
                }else{
                    $recipe->setPictureFilename("NO PICS");
                }

                $manager->persist($recipe);
                $manager->flush();

                return $this->redirectToRoute('app_recipe_view', ["slug" => $recipe->getSlug()]);
            }else{
                $this->redirectToRoute('app_login');
            }

        }

        return $this->render('recipes/form.html.twig',[
            'title' => "Création d'une recette",
            "form" => $recipeForm->createView()
        ]);
    }
}
