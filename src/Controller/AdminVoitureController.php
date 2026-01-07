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
    #[Route('/admin/voiture/new', name: 'admin_voiture_new')]
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
                    return $this->redirectToRoute('admin_voiture_new');
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
                    return $this->redirectToRoute('admin_voiture_new');
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
            return $this->redirectToRoute('admin_voiture_new'); // ou vers liste
        }

        return $this->render('admin/voiture/new.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
