<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Form\CommentFormType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/article', name: 'article_')]
class ArticleController extends AbstractController
{
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig');
    }

    #[Route('/{slug}', name: 'details')]
    public function details(
        Article $article,
        CommentRepository $commentRepository,
        Request $request,
        EntityManagerInterface $entityManager
        ): Response
    {
        // création d'un nouveau commentaire
        $comment = new Comment;
        //création du formulaire
        $form = $this->createForm(CommentFormType::class,$comment);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid())
        {
            $comment->setArticle($article);
            $entityManager->persist($comment);
            $entityManager->flush();
            $this->addFlash('success', 'Votre commentaire à bien été pris en compte');
            return $this->redirectToRoute('article_details',['slug' =>$article->getSlug()]);
        }


        $comments = $commentRepository->findBy(['article' => $article]);
        return $this->render('article/details.html.twig', [
            'article' => $article,
            'comments' => $comments,
            'commentForm' => $form->createView(),]);
    }
}
