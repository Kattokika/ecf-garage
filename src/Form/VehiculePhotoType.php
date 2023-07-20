<?php
namespace App\Form;

use App\Form\DataTransformer\VehiculeToNumberTransformer;
use App\Entity\VehiculePhoto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Validator\Constraints\File;

class VehiculePhotoType extends AbstractType
{
    public function __construct(
        private readonly VehiculeToNumberTransformer $transformer,
    ) {
    }
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('vehicule', HiddenType::class)
            ->add('picture', FileType::class,[
                    'mapped' => false,
                    'required' => false,
                    'constraints' => [
                        new File([
                            'maxSize' => '32M',
                            'mimeTypes' => [
                                'image/*',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid image document',
                        ])
                    ],
                ]);
        $builder->get('vehicule')
            ->addModelTransformer($this->transformer)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => VehiculePhoto::class,
            'attr' => ['id' => 'vehicule-photo'],
        ]);
    }

    public function getBlockPrefix(): string
    {
        # see https://stackoverflow.com/questions/65415444/symfony-5-form-issubmitted-returning-false-for-file-upload
        return '';
    }
}
