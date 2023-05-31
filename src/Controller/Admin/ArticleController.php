<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/admin/article', name: 'admin_article_')]
class ArticleController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger): Response
    {
        // on crée un nouvel article
        $article = new Article();

        // on crée le formulaire
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        // on traite la requête du formulaire
        $articleForm->handleRequest($request);

        // on génère le slug 
        // on vérifie si le formulaire et soumis et valide
        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $slug = $slugger->slug($article->getTitle());
            $article->setSlug($slug);

        // on enregistre en base de donnée

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été créé avec succès');

            // on redirige
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/add.html.twig',[
                'articleForm' => $articleForm->createView(),
            ]
        );
    }
    #[Route('/edition/{id}', name: 'edit')]
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger ): Response
    {
        // on crée le formulaire
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        // on traite la requête du formulaire
        $articleForm->handleRequest($request);

        // on génère le slug 
        // on vérifie si le formulaire et soumis et valide
        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            $slug = $slugger->slug($article->getTitle());
            $article->setSlug($slug);

        // on enregistre en base de donnée

            $entityManager->persist($article);
            $entityManager->flush();

            $this->addFlash('success', 'L\'article a été modifié avec succès');

            // on redirige
            return $this->redirectToRoute('admin_article_index');
        }

        return $this->render('admin/article/edit.html.twig',[
                'articleForm' => $articleForm->createView(),
            ]
        );
        return $this->render('admin/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/suppression/{id}', name: 'remove')]
    public function remove(Article $article): Response
    {
        return $this->render('admin/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
}
