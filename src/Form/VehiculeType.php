<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\VehiculeCarburant;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('marque')
            ->add('modele')
            ->add('kilometre')
            ->add('prix')
            ->add('annee')
            ->add('boite', ChoiceType::class, [
                'choices'  => [
                    'Automatique' => 'Automatique',
                    'Manuelle' => 'Manuelle',
                ],
            ])
            ->add('portes', ChoiceType::class, [
                'choices'  => [
                    '3' => '3',
                    '5' => '3',
                ],
            ])
            ->add('couleur')
            ->add('puissance')
            ->add('equipement')
            ->add('carburant', EntityType::class, [
                // looks for choices from this entity
                'class' => VehiculeCarburant::class,
                // uses the VehiculeCarburant.type property as the visible option string
                'choice_label' => 'type',
                // used to render a select box, check boxes or radios
                // 'multiple' => true,
                // 'expanded' => true,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
