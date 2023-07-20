<?php

namespace App\Form\DataTransformer;

use App\Entity\Vehicule;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Exception\TransformationFailedException;

class VehiculeToNumberTransformer implements DataTransformerInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
    ) {
    }

    /**
     * Transforms an object (vehicule) to a string (number).
     *
     * @param  Vehicule|null $vehicule
     */
    public function transform($vehicule): string
    {
        if (null === $vehicule) {
            return '';
        }

        return $vehicule->getId();
    }

    /**
     * Transforms a string (number) to an object (vehicule).
     *
     * @param  string $vehiculeNumber
     * @throws TransformationFailedException if object (vehicule) is not found.
     */
    public function reverseTransform($vehiculeNumber): ?Vehicule
    {
        if (!$vehiculeNumber) {
            return null;
        }

        $vehicule = $this->entityManager
            ->getRepository(Vehicule::class)
            ->find((int) $vehiculeNumber)
        ;
        // dd($vehicule);

        if (null === $vehicule) {
            // causes a validation error
            // this message is not shown to the user
            // see the invalid_message option
            throw new TransformationFailedException(sprintf(
                'An vehicule with number "%s" does not exist!',
                $vehiculeNumber
            ));
        }

        return $vehicule;
    }
}