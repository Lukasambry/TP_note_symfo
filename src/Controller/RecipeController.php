<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Repository\RecipeRepository;
use App\Service\CsvService;
use App\Service\PdfService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/recipe')]
#[IsGranted('ROLE_USER')]
final class RecipeController extends AbstractController
{
    #[Route(name: 'app_recipe_index', methods: ['GET'])]
    public function index(RecipeRepository $recipeRepository): Response
    {
        return $this->render('recipe/index.html.twig', [
            'recipes' => $recipeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_recipe_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($recipe);
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/new.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_show', methods: ['GET'])]
    public function show(Recipe $recipe): Response
    {
        return $this->render('recipe/show.html.twig', [
            'recipe' => $recipe,
            'steps' => $recipe->getSteps(),
        ]);
    }

    #[Route('/{id}/edit', name: 'app_recipe_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_recipe_delete', methods: ['POST'])]
    public function delete(Request $request, Recipe $recipe, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_recipe_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/recipe/{id}/pdf', name: 'app_recipe_pdf')]
    public function generatePdf(Recipe $recipe, PdfService $pdfService): Response
    {
        $html = $this->renderView('recipe/pdf.html.twig', [
            'recipe' => $recipe
        ]);

        $pdfService->showPdf($html);
        return new Response();
    }

    #[Route('/recipe/{id}/csv', name: 'app_recipe_csv')]
    public function exportCsv(Recipe $recipe, CsvService $csvService): Response
    {
        $data = [];

        // Informations de base de la recette
        $recipeData = [
            'Nom de la recette' => $recipe->getName(),
            'Description' => $recipe->getDescription(),
            'Temps de préparation' => $recipe->getPreparationTime() . ' minutes',
            'Difficulté' => $recipe->getDifficulty(),
            'Date de création' => $recipe->getCreatedAt()->format('d/m/Y'),
        ];
        $data[] = $recipeData;

        // Ligne vide pour la séparation
        $data[] = array_fill_keys(array_keys($recipeData), '');

        // Ingrédients
        $data[] = [
            'Nom de la recette' => 'INGRÉDIENTS',
            'Description' => '',
            'Temps de préparation' => '',
            'Difficulté' => '',
            'Date de création' => '',
        ];

        foreach ($recipe->getIngredients() as $ingredient) {
            $data[] = [
                'Nom de la recette' => $ingredient->getName(),
                'Description' => $ingredient->getQuality() . ' ' . $ingredient->getUnit(),
                'Temps de préparation' => '',
                'Difficulté' => '',
                'Date de création' => '',
            ];
        }

        // Ligne vide pour la séparation
        $data[] = array_fill_keys(array_keys($recipeData), '');

        // Étapes
        $data[] = [
            'Nom de la recette' => 'ÉTAPES',
            'Description' => '',
            'Temps de préparation' => '',
            'Difficulté' => '',
            'Date de création' => '',
        ];

        foreach ($recipe->getSteps() as $step) {
            $data[] = [
                'Nom de la recette' => 'Étape ' . $step->getOrderNumber(),
                'Description' => $step->getDescription(),
                'Temps de préparation' => '',
                'Difficulté' => '',
                'Date de création' => '',
            ];
        }

        return $csvService->generateCsvResponse($data, $recipe->getName() . '.csv');
    }
}
