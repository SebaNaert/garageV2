<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    // Marque du véhicule (ex : BMW)
    #[ORM\Column(length: 100)]
    private string $marque;

    // Modèle du véhicule (ex : Série 3)
    #[ORM\Column(length: 100)]
    private string $modele;

    // Kilométrage
    #[ORM\Column(type: 'integer')]
    private int $kilometrage;

    // Prix en euros
    #[ORM\Column(type: 'float')]
    private float $prix;

    // Nombre de propriétaires précédents
    #[ORM\Column(type: 'integer')]
    private int $nombreProprietaires;

    // Cylindrée (ex : 2.0L)
    #[ORM\Column(length: 50)]
    private string $cylindree;

    // Puissance en chevaux
    #[ORM\Column(type: 'integer')]
    private int $puissance;

    // Type de carburant (Essence, Diesel, Hybride...)
    #[ORM\Column(length: 50)]
    private string $carburant;

    // Année de mise en circulation
    #[ORM\Column(type: 'integer')]
    private int $anneeMiseEnCirculation;

    // Transmission (Manuelle / Automatique)
    #[ORM\Column(length: 50)]
    private string $transmission;

    // Description du véhicule
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $description = null;

    // Options (GPS, Climatisation, etc.)
    #[ORM\Column(type: 'text', nullable: true)]
    private ?string $options = null;

    // Image de couverture
    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageCouverture = null;

    /**
     * @var Collection<int, VoitureImage>
     */
    #[ORM\OneToMany(targetEntity: VoitureImage::class, mappedBy: 'voiture', cascade: ['persist', 'remove'])]
    private Collection $voitureImages;

    public function __construct()
    {
        $this->voitureImages = new ArrayCollection();
    }

    /* ================== GETTERS / SETTERS ================== */

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;
        return $this;
    }

    public function getModele(): string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;
        return $this;
    }

    public function getKilometrage(): int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;
        return $this;
    }

    public function getPrix(): float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getNombreProprietaires(): int
    {
        return $this->nombreProprietaires;
    }

    public function setNombreProprietaires(int $nombreProprietaires): self
    {
        $this->nombreProprietaires = $nombreProprietaires;
        return $this;
    }

    public function getCylindree(): string
    {
        return $this->cylindree;
    }

    public function setCylindree(string $cylindree): self
    {
        $this->cylindree = $cylindree;
        return $this;
    }

    public function getPuissance(): int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): self
    {
        $this->puissance = $puissance;
        return $this;
    }

    public function getCarburant(): string
    {
        return $this->carburant;
    }

    public function setCarburant(string $carburant): self
    {
        $this->carburant = $carburant;
        return $this;
    }

    public function getAnneeMiseEnCirculation(): int
    {
        return $this->anneeMiseEnCirculation;
    }

    public function setAnneeMiseEnCirculation(int $anneeMiseEnCirculation): self
    {
        $this->anneeMiseEnCirculation = $anneeMiseEnCirculation;
        return $this;
    }

    public function getTransmission(): string
    {
        return $this->transmission;
    }

    public function setTransmission(string $transmission): self
    {
        $this->transmission = $transmission;
        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;
        return $this;
    }

    public function getOptions(): ?string
    {
        return $this->options;
    }

    public function setOptions(?string $options): self
    {
        $this->options = $options;
        return $this;
    }

    public function getImageCouverture(): ?string
    {
        return $this->imageCouverture;
    }

    public function setImageCouverture(?string $imageCouverture): self
    {
        $this->imageCouverture = $imageCouverture;
        return $this;
    }

    /**
     * @return Collection<int, VoitureImage>
     */
    public function getVoitureImages(): Collection
    {
        return $this->voitureImages;
    }

    public function addVoitureImage(VoitureImage $voitureImage): static
    {
        if (!$this->voitureImages->contains($voitureImage)) {
            $this->voitureImages->add($voitureImage);
            $voitureImage->setVoiture($this);
        }

        return $this;
    }

    public function removeVoitureImage(VoitureImage $voitureImage): static
    {
        if ($this->voitureImages->removeElement($voitureImage)) {
            // set the owning side to null (unless already changed)
            if ($voitureImage->getVoiture() === $this) {
                $voitureImage->setVoiture(null);
            }
        }

        return $this;
    }
}