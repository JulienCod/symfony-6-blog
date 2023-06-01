<?php

namespace App\Controller\Admin;

use App\Entity\Article;
use App\Form\ArticleFormType;
use App\Service\PictureService;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/admin/article', name: 'admin_article_')]
class ArticleController extends AbstractController
{

    #[Route('/', name: 'index')]
    public function index(ArticleRepository $articleRepository): Response
    {
        $statuses = ['Actif', 'Inactif', 'Archive', 'Brouillon'];
        $articlesByStatus = $articleRepository->findBy(['status' => $statuses]);

        $articlesActif = [];
        $articlesInactif = [];
        $articlesArchive = [];
        $articlesBrouillon = [];

        foreach ($articlesByStatus as $article) {
            switch ($article->getStatus()) {
                case 'Actif':
                    $articlesActif[] = $article;
                    break;
                case 'Inactif':
                    $articlesInactif[] = $article;
                    break;
                case 'Archive':
                    $articlesArchive[] = $article;
                    break;
                case 'Brouillon':
                    $articlesBrouillon[] = $article;
                    break;
                default:
                    // Traitement en cas de statut inconnu
                    break;
            }
        }
        return $this->render('admin/article/index.html.twig', compact('articlesActif','articlesBrouillon','articlesInactif','articlesArchive'));
    }
    #[Route('/ajout', name: 'add')]
    public function add(Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        // on crée un nouvel article
        $article = new Article();

        // on crée le formulaire
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        // on traite la requête du formulaire
        $articleForm->handleRequest($request);

        // on vérifie si le formulaire et soumis et valide
        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            
            // on récupère les images
            $image = $articleForm->get('image')->getData();

            if ($image) {
                //on défini le dossier de destination
                $folder = 'articles';
    
                // on appelle le service d'ajout
                $fichier = $pictureService->add($image,$folder,300,300);
    
                $article->setImage($fichier);
            }


            // on génère le slug 
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
    public function edit(Article $article, Request $request, EntityManagerInterface $entityManager, SluggerInterface $slugger, PictureService $pictureService): Response
    {
        // on crée le formulaire
        $articleForm = $this->createForm(ArticleFormType::class, $article);

        // on traite la requête du formulaire
        $articleForm->handleRequest($request);

        // on vérifie si le formulaire et soumis et valide
        if ($articleForm->isSubmitted() && $articleForm->isValid())
        {
            // on récupère les images
            $image = $articleForm->get('image')->getData();
            
            if ($image) {
                //on défini le dossier de destination
                $folder = 'articles';

                // on supprime l'ancienne image
                if ($article->getImage()) {
                    $pictureService->delete($article->getImage(), $folder, 300,300);
                }
            
                // on appelle le service d'ajout
                $fichier = $pictureService->add($image,$folder,300,300);
                
                $article->setImage($fichier);
            }

            
            
            // on génère le slug 
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
                'article' => $article,
            ]
        );
        return $this->render('admin/article/index.html.twig', [
            'controller_name' => 'ArticleController',
        ]);
    }
    #[Route('/suppression/{id}', name: 'remove')]
    public function delete(Article $article): Response
    {
        return $this->render('admin/article/index.html.twig');
    }

}
