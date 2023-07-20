<?php

namespace App\Entity;

use App\Repository\HorairesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HorairesRepository::class)]
class Horaires
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private ?int $position = null;

    #[ORM\Column(length: 10)]
    private ?string $nom_jour = null;

    #[ORM\Column]
    private ?bool $ouverture = null;

    #[ORM\Column(type: Types::ARRAY)]
    private array $horaires = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPosition(): ?int
    {
        return $this->position;
    }

    public function setPosition(int $position): static
    {
        $this->position = $position;

        return $this;
    }

    public function getNomJour(): ?string
    {
        return $this->nom_jour;
    }

    public function setNomJour(string $nom_jour): static
    {
        $this->nom_jour = $nom_jour;

        return $this;
    }

    public function isOuverture(): ?bool
    {
        return $this->ouverture;
    }

    public function setOuverture(bool $ouverture): static
    {
        $this->ouverture = $ouverture;

        return $this;
    }

    public function getHoraires(): array
    {
        return $this->horaires;
    }

    public function setHoraires(array $horaires): static
    {
        $this->horaires = $horaires;

        return $this;
    }
}
