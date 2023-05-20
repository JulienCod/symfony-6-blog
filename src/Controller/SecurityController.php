<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    
    #[Route("/connexion", name:"app_login", methods: ['GET', 'POST'])]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

    // Récupérer les erreurs d'authentification et le dernier nom d'utilisateur entré
    $error = $authenticationUtils->getLastAuthenticationError();
    $lastUsername = $authenticationUtils->getLastUsername();

    return $this->render('welcome/welcome.html.twig', [
        'last_username' => $lastUsername,
        'error' => $error,
    ]);
    }

    
    #[Route("/deconnexion", name:"app_logout")]
    
    public function logout(): void
    {
        // Cette méthode peut rester vide,
        // car elle sera gérée par le système de déconnexion de Symfony.
    }
}