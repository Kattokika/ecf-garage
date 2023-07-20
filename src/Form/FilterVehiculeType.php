<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prixMin', IntegerType::class)
            ->add('prixMax', IntegerType::class)
            ->add('anneeMin', IntegerType::class)
            ->add('anneeMax', IntegerType::class)
            ->add('kmMin', IntegerType::class)
            ->add('kmMax', IntegerType::class)
            # TODO: ajouter un champ recherche texte sur modele + marque
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
