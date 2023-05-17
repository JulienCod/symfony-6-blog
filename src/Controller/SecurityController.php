<?php 

namespace App\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    
    #[Route("/login", name:"app_login")]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Récupérer les erreurs d'authentification, le dernier nom d'utilisateur entré
        // et le dernier message d'erreur (s'il y en a) depuis le service AuthenticationUtils
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('welcome/welcome.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
        ]);
    }

    
    #[Route("/logout", name:"logout")]
    
    public function logout(): void
    {
        // Cette méthode peut rester vide,
        // car elle sera gérée par le système de déconnexion de Symfony.
    }
}