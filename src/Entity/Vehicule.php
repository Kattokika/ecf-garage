<?php

namespace App\Entity;

use App\Repository\VehiculeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VehiculeRepository::class)]
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
}
