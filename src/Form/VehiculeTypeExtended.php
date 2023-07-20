<?php

namespace App\Form;

use App\Entity\Vehicule;
use App\Entity\VehiculePhoto;
# use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class VehiculeTypeExtended extends VehiculeType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        parent::buildForm($builder, $options);

        $builder->add('thumbnail', EntityType::class, [
            'class' => VehiculePhoto::class,
            'choices' => $builder->getData()->getPhotos(),
            'expanded' => true,
            'multiple' => false,
            'choice_label' => 'id',
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Vehicule::class,
        ]);
    }
}
