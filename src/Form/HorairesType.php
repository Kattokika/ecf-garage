<?php

namespace App\Form;

use App\Entity\Horaires;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\DataMapperInterface;
use Symfony\Component\Form\Exception\UnexpectedTypeException;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

#class HorairesType extends AbstractType
class HorairesType extends AbstractType implements DataMapperInterface
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            # ->add('position')
            # ->add('nom_jour')
            ->add('ouverture')
            # ->add('horaires')
            ->add('morning_open', TimeType::class, [
                'input' => 'string',
                'minutes' => [0, 30],
            ])
            ->add('morning_close', TimeType::class, [
                'input' => 'string',
                'minutes' => [0, 30],
            ])
            ->add('afternoon_open', TimeType::class, [
                'input' => 'string',
                'minutes' => [0, 30],
            ])
            ->add('afternoon_close', TimeType::class, [
                'input' => 'string',
                'minutes' => [0, 30],
            ])
            ->setDataMapper($this)
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Horaires::class,
        ]);
    }


    /**
     * @param Horaires|null $viewData
     */
    public function mapDataToForms($viewData, \Traversable $forms): void
    {
        // there is no data yet, so nothing to prepopulate
        if (null === $viewData) {
            return;
        }

        // invalid data type
        if (!$viewData instanceof Horaires) {
            throw new UnexpectedTypeException($viewData, Horaires::class);
        }

        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);
        // initialize form field values
        $forms['morning_open']->setData($viewData->getHorairesFromIndex(0));
        $forms['morning_close']->setData($viewData->getHorairesFromIndex(1));
        $forms['afternoon_open']->setData($viewData->getHorairesFromIndex(2));
        $forms['afternoon_close']->setData($viewData->getHorairesFromIndex(3));
        $forms['ouverture']->setData(($viewData->isOuverture()));
    }

    /**
     * @param Horaires|null $viewData
     */
    public function mapFormsToData(\Traversable $forms, &$viewData): void
    {
        /** @var FormInterface[] $forms */
        $forms = iterator_to_array($forms);

        $viewData->setOuverture($forms['ouverture']->getData());
        $viewData->setHoraires([
            $forms['morning_open']->getData(),
            $forms['morning_close']->getData(),
            $forms['afternoon_open']->getData(),
            $forms['afternoon_close']->getData(),
        ]);
    }
}
