<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserFormType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\User\UserInterface;

#[Route('/compte', name: 'user_')]
class UserController extends AbstractController
{
    /**
     * @param User $user
     * @param UserInterface $currentUser
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    #[Route('/edition/{id}', name: 'edit')]
    public function index(
        User $user,
        UserInterface $currentUser,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response
    {
        if ($currentUser->getId() !== $user->getId())
        {
            $this->addFlash('warning', 'Vous n\'avez pas accès à cette page');
            return $this->redirectToRoute('app_welcome');
        }
        $form = $this->createForm(UserFormType::class,$user);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid())
        {
            $entityManager->persist($user);
            $entityManager->flush();
            $this->addFlash('success', 'Votre compte à été modifier avec sucess');
            return $this->redirectToRoute('user_edit',['id' => $user->getId()]);
        }

        return $this->render('user/edit.html.twig',[
            'user' =>   $user,
            'userForm' => $form->createView()
        ]);
    }
}
