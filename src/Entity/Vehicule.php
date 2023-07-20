<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\String\Slugger\SluggerInterface;

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

    public function __construct()
    {
        $this->photos = new ArrayCollection();
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
}
