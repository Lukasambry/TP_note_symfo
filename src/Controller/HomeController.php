<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(RecipeRepository $recipeRepository): Response
    {
        $latestRecipes = $recipeRepository->findLatestRecipes();
        $popularRecipes = $recipeRepository->findPopularRecipes();

        return $this->render('./index.html.twig', [
            'latest_recipes' => $latestRecipes,
            'popular_recipes' => $popularRecipes,
        ]);
    }
}
