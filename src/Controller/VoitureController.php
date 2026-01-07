<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Repository\VoitureRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class VoitureController extends AbstractController
{
    /**
     * Affiche la liste de toutes les voitures.
     *
     * @param VoitureRepository $repo Repository pour accéder aux voitures
     * @return Response Réponse HTTP avec le rendu du template 'voiture/index.html.twig'
     */
    #[Route('/voitures', name: 'voiture_index')]
    public function index(VoitureRepository $repo): Response
    {
        $voitures = $repo->findAll();
        return $this->render('voiture/index.html.twig', [
            'voitures' => $voitures,
        ]);
    }
    /**
     * Affiche les détails d'une voiture spécifique.
     *
     * @param Voiture $voiture Entité Voiture récupérée automatiquement par ParamConverter
     * @return Response Réponse HTTP avec le rendu du template 'voiture/show.html.twig'
     */
    #[Route('/voitures/{id}', name: 'voiture_show')]
    public function show(Voiture $voiture): Response
    {
        return $this->render('voiture/show.html.twig', [
            'voiture' => $voiture,
        ]);
    }
}
