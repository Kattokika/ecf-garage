<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ResetType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterVehiculeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('prixMin', IntegerType::class, [
                'required'=> false,
            ])
            ->add('prixMax', IntegerType::class, [
                'required'=> false,
            ])
            ->add('anneeMin', IntegerType::class, [
                'required'=> false,
            ])
            ->add('anneeMax', IntegerType::class, [
                'required'=> false,
            ])
            ->add('kmMin', IntegerType::class, [
                'required'=> false,
            ])
            ->add('kmMax', IntegerType::class, [
                'required'=> false,
            ])
            ->add('page', HiddenType::class, [
                'required'=> false,
            ])
            # TODO: ajouter un champ recherche texte sur modele + marque
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            // Configure your form options here
        ]);
    }

    public function getBlockPrefix(): string
    {
        return '';
    }
}
