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

    /**
     * @return Response
     */
    #[Route('/', name: 'index')]
    public function index(): Response
    {
        return $this->render('article/index.html.twig');
    }

    /**
     * @param Article $article
     * @param CommentRepository $commentRepository
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
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
        $stopBot = $request->request->get('robot_check');
        $stopBot1 = $request->request->get('robot_check_1');

            if ($form->isSubmitted() && $form->isValid() && $stopBot === "123456789" && $stopBot1 === "")
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
