<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Validator\Constraints as Assert;

# TODO: add missing fields for vehicule
#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
#[UniqueEntity('slug')]
class Vehicule
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\OneToMany(mappedBy: 'vehicule', targetEntity: VehiculePhoto::class, orphanRemoval: true)]
    private Collection $photos;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?VehiculeCarburant $carburant = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $slug = null;

    #[ORM\Column(length: 64)]
    private ?string $marque = null;

    #[ORM\Column(length: 255)]
    private ?string $modele = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column]
    private ?int $kilometre = null;
    #[Assert\PositiveOrZero]
    #[ORM\Column]
    private ?int $prix = null;

    #[Assert\GreaterThan(1900)]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $annee = null;
    #[Assert\Choice(
        choices: ['Manuelle', 'Automatique'],
        message: 'Choisir un type valide.',
    )]
    #[ORM\Column(length: 32)]
    private ?string $boite = null;

    #[Assert\Choice(
        choices: ['3', '5'],
        message: 'Choisir entre 3 et 5 portes.',
    )]
    #[ORM\Column(length: 2)]
    private ?string $portes = null;

    #[ORM\Column(length: 32)]
    private ?string $couleur = null;

    #[Assert\PositiveOrZero]
    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $puissance = null;

    #[ORM\OneToOne(cascade: ['persist', 'remove'], orphanRemoval: true)]
    private ?VehiculePhoto $thumbnail = null;

    public function __construct()
    {
        $this->photos = new ArrayCollection();
    }

    public function __toString(): string
    {
        return $this->marque.' '.$this->modele.' '.$this->annee.' '.$this->id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return Collection<int, VehiculePhoto>
     */
    public function getPhotos(): Collection
    {
        return $this->photos;
    }

    public function addPhoto(VehiculePhoto $photo): static
    {
        if (!$this->photos->contains($photo)) {
            $this->photos->add($photo);
            $photo->setVehicule($this);
        }

        return $this;
    }

    public function removePhoto(VehiculePhoto $photo): static
    {
        if ($this->photos->removeElement($photo)) {
            // set the owning side to null (unless already changed)
            if ($photo->getVehicule() === $this) {
                $photo->setVehicule(null);
            }
        }

        return $this;
    }

    public function removeAllPhotos(): static
    {
        foreach ($this->photos as $photo) {
            if ($this->photos->removeElement($photo)) {
                // set the owning side to null (unless already changed)
                if ($photo->getVehicule() === $this) {
                    $photo->setVehicule(null);
                }
            }
        }

        return $this;
    }

    public function getCarburant(): ?VehiculeCarburant
    {
        return $this->carburant;
    }

    public function setCarburant(?VehiculeCarburant $carburant): static
    {
        $this->carburant = $carburant;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(string $slug): static
    {
        $this->slug = $slug;

        return $this;
    }
    public function computeSlug(SluggerInterface $slugger)
    {
        if (!$this->slug || '-' === $this->slug) {
            $this->slug = (string) $slugger->slug((string) $this)->lower();
        }
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): static
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): static
    {
        $this->modele = $modele;

        return $this;
    }

    public function getKilometre(): ?int
    {
        return $this->kilometre;
    }

    public function setKilometre(int $kilometre): static
    {
        $this->kilometre = $kilometre;

        return $this;
    }

    public function getPrix(): ?int
    {
        return $this->prix;
    }

    public function setPrix(int $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getAnnee(): ?int
    {
        return $this->annee;
    }

    public function setAnnee(int $annee): static
    {
        $this->annee = $annee;

        return $this;
    }

    public function getBoite(): ?string
    {
        return $this->boite;
    }

    public function setBoite(string $boite): static
    {
        $this->boite = $boite;

        return $this;
    }

    public function getPortes(): ?string
    {
        return $this->portes;
    }

    public function setPortes(string $portes): static
    {
        $this->portes = $portes;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): static
    {
        $this->couleur = $couleur;

        return $this;
    }

    public function getPuissance(): ?int
    {
        return $this->puissance;
    }

    public function setPuissance(int $puissance): static
    {
        $this->puissance = $puissance;

        return $this;
    }

    public function getThumbnail(): ?VehiculePhoto
    {
        return $this->thumbnail;
    }

    public function setThumbnail(?VehiculePhoto $thumbnail): static
    {
        $this->thumbnail = $thumbnail;

        return $this;
    }
}
