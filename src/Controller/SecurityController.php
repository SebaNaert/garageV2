<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractController
{
    /**
     * Affiche le formulaire de connexion et gère les erreurs de login.
     * Redirige vers la liste des voitures si l'utilisateur est déjà connecté.
     *
     * @param AuthenticationUtils $authenticationUtils Service fournissant l'état de l'authentification
     * @return Response Réponse HTTP avec le formulaire de connexion
     */
    #[Route(path: '/login', name: 'login')]
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        // Redirige si déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('voiture_index');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error
        ]);
    }
    /**
     * Déconnecte l'utilisateur.
     *
     * @return void
     */
    #[Route(path: '/logout', name: 'logout')]
    public function logout(): void
    {
    }
}
