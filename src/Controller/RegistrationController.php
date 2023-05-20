<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use Symfony\Component\HttpFoundation;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'registration')]
    public function register(Request $request, UserPasswordHasherInterface $passwordEncoder, EntityManagerInterface $entityManager): Response
    {

        $form = $this->createForm(RegistrationFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Traitement des données du formulaire et enregistrement de l'utilisateur
            $user = $form->getData();

             // Vérifier si l'adresse email est déjà utilisée
             $existingUser = $entityManager->getRepository(User::class)->findOneBy(['email' => $user->getEmail()]);
             if ($existingUser) {
                // Ajouter un flash message en cas d'adresse email déjà utilisée
                $this->addFlash('error-mail', 'Cette adresse email est déjà utilisée');

                // Redirection vers une autre page pour afficher le message d'erreur
                return $this->redirectToRoute('registration');
             }

            // Encoder le mot de passe
            $password = $passwordEncoder->hashPassword($user, $user->getPassword());
            $user->setPassword($password);

            // Attribuer le rôle à l'utilisateur
            $user->setRoles(['ROLE_USER']);

            // Enregistrer l'utilisateur en base de données
            $entityManager->persist($user);
            $entityManager->flush();
            // Redirection vers une autre page après l'inscription
            return $this->redirectToRoute('registration_success');
        }

    
        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/registration_success', name:'registration_success')]
    public function registrationSuccess(): Response
    {
        return $this->render('registration/success.html.twig');
    }
}
