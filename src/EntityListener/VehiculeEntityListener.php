<?php

namespace App\EntityListener;

use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use App\Entity\Vehicule;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Symfony\Component\String\Slugger\SluggerInterface;

#[AsEntityListener(event: Events::prePersist, entity: Vehicule::class)]
#[AsEntityListener(event: Events::preUpdate, entity: Vehicule::class)]
class VehiculeEntityListener
{
    public function __construct(
        private SluggerInterface $slugger,
    ) {
    }

    public function prePersist(Vehicule $conference, LifecycleEventArgs $event): void
    {
        $conference->computeSlug($this->slugger);
    }

    public function preUpdate(Vehicule $conference, LifecycleEventArgs $event): void
    {
        $conference->computeSlug($this->slugger);
    }
}
