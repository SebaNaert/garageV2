<?php

namespace App\Controller;

use App\Entity\Voiture;
use App\Entity\VoitureImage;
use App\Form\VoitureType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

final class AdminVoitureController extends AbstractController
{
    /**
     * Permet de créer une nouvelle voiture.
     * Gère l'upload de l'image de couverture et de la galerie d'images.
     *
     * @param Request $request Requête HTTP
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @param SluggerInterface $slugger Service pour sécuriser les noms de fichiers
     * @return Response Réponse HTTP avec le formulaire ou redirection après succès
     */
    #[Route('/new', name: 'admin_voiture_new')]
    #[IsGranted("ROLE_ADMIN")]
    public function new(Request $request, EntityManagerInterface $em, SluggerInterface $slugger): Response
    {
        $voiture = new Voiture();
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // --- IMAGE DE COUVERTURE ---
            $coverFile = $form->get('imageCouverture')->getData();
            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                try {
                    $coverFile->move(
                        $this->getParameter('voitures_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'image de couverture.');
                }

                $voiture->setImageCouverture($newFilename);
            }

            // --- GALERIE D'IMAGES ---
            foreach ($form->get('voitureImages') as $imageForm) {
            /** @var UploadedFile|null $imageFile */
            $imageFile = $imageForm->get('imageName')->getData(); // <-- récupérer depuis le formulaire

            if ($imageFile) {
                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('voitures_images_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('danger', 'Erreur lors de l\'upload de l\'une des images de la galerie.');
                }

                // Récupère l'entité VoitureImage liée au formulaire
                $imageEntity = $imageForm->getData();
                $imageEntity->setImageName($newFilename);
                $imageEntity->setVoiture($voiture); // relation inverse
            }
            }

            $em->persist($voiture); // cascade persist gère aussi la galerie
            $em->flush();

            $this->addFlash('success', 'Voiture ajoutée avec succès !');
            return $this->redirectToRoute('home'); // ou vers liste
        }

        return $this->render('admin/voiture/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * Permet de modifier une voiture existante.
     * Gère également l'upload de l'image de couverture et de la galerie d'images.
     *
     * @param Request $request Requête HTTP
     * @param Voiture $voiture Entité Voiture récupérée automatiquement par ParamConverter
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @param SluggerInterface $slugger Service pour sécuriser les noms de fichiers
     * @return Response Réponse HTTP avec le formulaire ou redirection après succès
     */
    #[Route('voitures/{id}/edit', name: 'admin_voiture_edit')]
    #[IsGranted("ROLE_ADMIN")]
    public function edit(
        Request $request,
        Voiture $voiture, // ParamConverter récupère automatiquement l'entité
        EntityManagerInterface $em,
        SluggerInterface $slugger
    ): Response
    {
        // Créer le formulaire avec l'entité existante
        $form = $this->createForm(VoitureType::class, $voiture);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // --- gérer image de couverture ---
            $coverFile = $form->get('imageCouverture')->getData();
            if ($coverFile) {
                $originalFilename = pathinfo($coverFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename.'-'.uniqid().'.'.$coverFile->guessExtension();

                $coverFile->move(
                    $this->getParameter('voitures_images_directory'),
                    $newFilename
                );

                $voiture->setImageCouverture($newFilename);
            }

            // --- gérer galerie ---
            foreach ($form->get('voitureImages') as $imageForm) {
                $imageFile = $imageForm->get('imageName')->getData();
                if ($imageFile) {
                    $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                    $safeFilename = $slugger->slug($originalFilename);
                    $newFilename = $safeFilename.'-'.uniqid().'.'.$imageFile->guessExtension();

                    $imageFile->move(
                        $this->getParameter('voitures_images_directory'),
                        $newFilename
                    );

                    $imageEntity = $imageForm->getData();
                    $imageEntity->setImageName($newFilename);
                    $imageEntity->setVoiture($voiture);
                }
            }

            $em->persist($voiture);
            $em->flush();

            $this->addFlash('success', 'Voiture modifiée avec succès !');
            return $this->redirectToRoute('voiture_index');
        }

        return $this->render('voiture/edit.html.twig', [
            'form' => $form->createView(),
            'voiture' => $voiture
        ]);
    }
    
    /**
     * Permet de supprimer une voiture et toutes ses images (couverture + galerie).
     * Vérifie le token CSRF avant suppression.
     *
     * @param Request $request Requête HTTP
     * @param Voiture $voiture Entité Voiture à supprimer
     * @param EntityManagerInterface $em Gestionnaire d'entités Doctrine
     * @return Response Redirection vers la liste des voitures après suppression
     */
    #[Route('voitures/{id}/delete', name: 'admin_voiture_delete', methods: ['POST'])]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Voiture $voiture, EntityManagerInterface $em): Response
    {
        // Vérifie le token CSRF
        if ($this->isCsrfTokenValid('delete'.$voiture->getId(), $request->request->get('_token'))) {

            // Supprimer l'image de couverture du serveur si elle existe
            $coverPath = $this->getParameter('voitures_images_directory') . '/' . $voiture->getImageCouverture();
            if ($voiture->getImageCouverture() && file_exists($coverPath)) {
                unlink($coverPath);
            }

            // Supprimer toutes les images de la galerie du serveur
            foreach ($voiture->getVoitureImages() as $image) {
                $imagePath = $this->getParameter('voitures_images_directory') . '/' . $image->getImageName();
                if ($image->getImageName() && file_exists($imagePath)) {
                    unlink($imagePath);
                }
            }

            $em->remove($voiture);
            $em->flush();

            $this->addFlash('success', 'La voiture a été supprimée avec toutes ses images.');
        }

        return $this->redirectToRoute('voiture_index');
    }

}
