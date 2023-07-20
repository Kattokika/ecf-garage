<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\VehiculePhoto;
# use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeTypeExtendedDeletePhoto extends VehiculeType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder->add('pictures', EntityType::class, [
            'class' => VehiculePhoto::class,
            'choices' => $options['vehicule']->getPhotos(),
            'expanded' => true,
            'multiple' => true,
            'choice_label' => 'id',
            'mapped'=> false,
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
            'vehicule' => null,
        ]);
    }
}
