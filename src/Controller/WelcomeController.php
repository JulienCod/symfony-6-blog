<?php

namespace App\Controller;

use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class WelcomeController extends AbstractController
{
    #[Route('/', name: 'app_welcome')]
    public function index(
        CategoryRepository $categoryRepository,
        ArticleRepository $articleRepository): Response
    {
        
        return $this->render('welcome/welcome.html.twig', [
            'categories' => $categoryRepository->findBy([],['categoryOrder' => 'asc']),
            'articles' => $articleRepository->findBy(
                ['status' => 'Actif'], // Critère de recherche : statut "Actif"
                ['createdAt' => 'desc'], // Critère de tri : date de création décroissante
                6 // Limite de résultats : 6 articles
            ),
        ]);
    }
}