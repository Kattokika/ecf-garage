<?php

namespace App\Form;

use App\Entity\Avis;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class AvisType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        if (!$options['valider_avis']) {
            # The formulaire est nécessaire pour le client
            $builder
                ->add('note', ChoiceType::class, [
                    'choices'  => [
                        'Selectionner une note sur 5' => '',
                        '1' => '1',
                        '2' => '2',
                        '3' => '3',
                        '4' => '4',
                        '5' => '5',
                    ],
                    'required' => true,
                ])
                ->add('nom')
                ->add('date_visite', DateType::class, [
                    'widget' => 'single_text',
                ])
                ->add('titre')
                ->add('commentaire')
            ;
        }
        else {
            # Ceci est le formulaire nécessaire à l'employé pour valider l'avis
            $builder
                ->add('reponse')
                ->add('status', ChoiceType::class, [
                    'choices'  => [
                        'Accepté' => 'accepted',
                        'Refusé' => 'refused',
                    ],
                ])
            ;
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Avis::class,
            'valider_avis' => false,
        ]);
    }
}
