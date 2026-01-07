<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "La marque est obligatoire")]
    #[Assert\Length(min:2, max:100, minMessage:"La marque doit faire au moins {{ limit }} caractères", maxMessage:"La marque ne peut pas dépasser {{ limit }} caractères")]
    private ?string $marque = null;

    #[ORM\Column(length: 100)]
    #[Assert\NotBlank(message: "Le modèle est obligatoire")]
    #[Assert\Length(min:1, max:100, minMessage:"Le modèle doit faire au moins {{ limit }} caractère", maxMessage:"Le modèle ne peut pas dépasser {{ limit }} caractères")]
    private ?string $modele = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message:"Le kilométrage est obligatoire")]
    #[Assert\PositiveOrZero(message:"Le kilométrage doit être positif ou nul")]
    private ?int $kilometrage = null;

    #[ORM\Column(type: 'float')]
    #[Assert\NotNull(message:"Le prix est obligatoire")]
    #[Assert\Positive(message:"Le prix doit être positif")]
    private ?float $prix = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message:"Le nombre de propriétaires est obligatoire")]
    #[Assert\PositiveOrZero(message:"Le nombre de propriétaires doit être positif ou nul")]
    private ?int $nombreProprietaires = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"La cylindrée est obligatoire")]
    #[Assert\Length(max:50, maxMessage:"La cylindrée ne peut pas dépasser {{ limit }} caractères")]
    private ?string $cylindree = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message:"La puissance est obligatoire")]
    #[Assert\Positive(message:"La puissance doit être positive")]
    private ?int $puissance = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Le carburant est obligatoire")]
    #[Assert\Choice(choices:["Essence","Diesel","Hybride","Électrique"], message:"Le carburant doit être l'un des suivants : Essence, Diesel, Hybride, Électrique")]
    private ?string $carburant = null;

    #[ORM\Column(type: 'integer')]
    #[Assert\NotNull(message:"L'année de mise en circulation est obligatoire")]
    #[Assert\Range(min:1900, max:2100, notInRangeMessage:"L'année doit être comprise entre {{ min }} et {{ max }}")]
    private ?int $anneeMiseEnCirculation = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"La transmission est obligatoire")]
    #[Assert\Choice(choices:["Manuelle","Automatique"], message:"La transmission doit être Manuelle ou Automatique")]
    private ?string $transmission = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(min:10, max:1000, minMessage:"La description doit faire au moins {{ limit }} caractères", maxMessage:"La description ne peut pas dépasser {{ limit }} caractères")]
    private ?string $description = null;

    #[ORM\Column(type: 'text', nullable: true)]
    #[Assert\Length(max:500, maxMessage:"Les options ne peuvent pas dépasser {{ limit }} caractères")]
    private ?string $options = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $imageCouverture = null;

    /**
     * @var Collection<int, VoitureImage>
     */
    #[ORM\OneToMany(targetEntity: VoitureImage::class, mappedBy: 'voiture', cascade: ['persist', 'remove'], orphanRemoval: true)]
    #[Assert\Valid()]
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

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(?string $marque): self
    {
        $this->marque = $marque;
        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(?string $modele): self
    {
        $this->modele = $modele;
        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(?int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;
        return $this;
    }

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(?float $prix): self
    {
        $this->prix = $prix;
        return $this;
    }

    public function getNombreProprietaires(): ?int
    {
        return $this->nombreProprietaires;
    }

    public function setNombreProprietaires(?int $nombreProprietaires): self
    {
        $this->nombreProprietaires = $nombreProprietaires;
        return $this;
    }

    public function getCylindree(): ?string
    {
        return $this->cylindree;
    }

    public function setCylindree(?string $cylindree): self
    {
        $this->cylindree = $cylindree;
        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(?int $puissance): self
    {
        $this->puissance = $puissance;
        return $this;
    }

    public function getCarburant(): ?string
    {
        return $this->carburant;
    }

    public function setCarburant(?string $carburant): self
    {
        $this->carburant = $carburant;
        return $this;
    }

    public function getAnneeMiseEnCirculation(): ?int
    {
        return $this->anneeMiseEnCirculation;
    }

    public function setAnneeMiseEnCirculation(?int $anneeMiseEnCirculation): self
    {
        $this->anneeMiseEnCirculation = $anneeMiseEnCirculation;
        return $this;
    }

    public function getTransmission(): ?string
    {
        return $this->transmission;
    }

    public function setTransmission(?string $transmission): self
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
            if ($voitureImage->getVoiture() === $this) {
                $voitureImage->setVoiture(null);
            }
        }
        return $this;
    }
}
