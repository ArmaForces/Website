<?php

declare(strict_types=1);

namespace App\Form\ModList;

use App\Form\ModList\Dto\ModListFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListFormType extends AbstractType
{
    /**
     * @param string[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ModListFormDto $modListFormDto */
        $modListFormDto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod list name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod list description',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModListFormDto::class,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                /** @var ModListFormDto $modListFormDto */
                $modListFormDto = $form->getData();

                return $modListFormDto->resolveValidationGroups();
            },
        ]);
    }
}
