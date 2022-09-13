<?php

namespace App\Controller;

use App\Entity\Recipe;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    #[Route('/search/{recipe}', name: 'app_search', methods: 'GET')]
    public function index(ManagerRegistry $registry, string $recipe): Response
    {
        return $this->render('search/index.html.twig', [
            'title'=>$recipe
        ]);
    }
}
