<?php

namespace App\Controller;

use App\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/categories', name: 'categories_')]
class CategoryController extends AbstractController
{

    /**
     * @param Category $category
     * @return Response
     */
    #[Route('/{slug}', name: 'list')]
    public function list(Category $category): Response
    {
        // récupération de la liste des article de la catégorie

        $articles = $category->getArticles();

        return $this->render('category/list.html.twig', [
            'category' => $category,
            'articles' => $articles,
        ]);
    }
}
