<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\AppAuthenticator;
use App\Service\JWTService;
use App\Service\SendMailService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request,
        UserPasswordHasherInterface $userPasswordHasher,
        UserAuthenticatorInterface $userAuthenticator,
        AppAuthenticator $authenticator,
        EntityManagerInterface $entityManager,
        SendMailService $mail,
        JWTService $jwt,
        ): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        // dd($form);
        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $entityManager->persist($user);
            $entityManager->flush();

            // on génère le jwt de l'utilisateur
            // on créer le header
            $header =[
                'typ' => 'JWT',
                'alg' => 'HS256',
            ];

            // on créer le payload
            $payload = [
                'user_id' => $user->getId(),
            ];

            // on génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'));

            // on envoie le mail

        $mail->send(
            'no-reply@monSite.com',
            $user->getEmail(),
            'Activation de votre compte sur le blog',
            'register',
            [
                'user' => $user,
                'token' => $token,
            ]
            );

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verif/{token}', name: 'verify_user')]
    public function verifyUser(
        $token,
        JWTService $jwt,
        UserRepository $userRepository,
        EntityManagerInterface $em): Response
    {
        // on vérifie si le token est valide, n'a pas expiré et n'a pas été modifié
        if ($jwt->isValid($token) && !$jwt->isExpired($token) &&
        $jwt->check($token, $this->getParameter('app.jwtsecret') ))
        {
            // on récupère le payload
            $payload = $jwt->getPayload($token);

            // on récupère le user du token
            $user = $userRepository->find($payload['user_id']);

            // on vérifie que l'utilisateur existe et n'a pas encore activé son compte
            if ($user && !$user->getIsVerified())
            {
                $user->setIsVerified(true);
                $em->flush($user);
                $this->addFlash('success', 'Utilisateur activé');
                return $this->redirectToRoute('app_welcome');
            }

        }
        // ici un problème se pose dans le token
        $this->addFlash('alert', 'Le token est invalide ou a expiré');
        return $this->redirectToRoute('app_login');
    }

    #[Route("/renvoiverif", name: 'resend_verif')]
    public function resendVerif(
        JWTService $jwt,
        SendMailService $mail,
        UserRepository $userRepository
        ): Response
    {
        $user = $this->getUser();

        if (!$user) 
        {
            $this->addFlash('alert', 'Vous devez être connecté pour accéder à cette page');
            return $this->redirectToRoute('app_login');
        }

        if ($user->getIsVerified()) 
        {
            $this->addFlash('info', 'Cet utilisateur est déjà activé');
            return $this->redirectToRoute('app_welcome');
        }

        // on génère le jwt de l'utilisateur
            // on créer le header
            $header =[
                'typ' => 'JWT',
                'alg' => 'HS256',
            ];

            // on créer le payload
            $payload = [
                'user_id' => $user->getId(),
            ];

            // on génère le token
            $token = $jwt->generate($header, $payload, $this->getParameter('app.jwtsecret'), 10800);

            $mail->send(
                'no-reply@monSite.com',
                $user->getEmail(),
                'Activation de votre compte sur le blog',
                'register',
                [
                    'user' => $user,
                    'token' => $token,
                ]
            );

            $this->addFlash('success', 'e-mail de vérification envoyé');
            return $this->redirectToRoute('app_welcome');
    }

}
