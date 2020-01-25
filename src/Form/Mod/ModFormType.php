<?php

declare(strict_types=1);

namespace App\Form\Mod;

use App\Enum\Mod\ModSourceEnum;
use App\Enum\Mod\ModTypeEnum;
use App\Enum\Mod\ModUsedByEnum;
use App\Form\Mod\Dto\ModFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModFormType extends AbstractType
{
    /**
     * @param string[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ModFormDto $modFormDto */
        $modFormDto = $builder->getData();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Name',
            ])
            ->add('usedBy', ChoiceType::class, [
                'label' => 'Used by',
                'choices' => [
                    'Client' => ModUsedByEnum::CLIENT,
                    'Server' => ModUsedByEnum::SERVER,
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Type',
                'choices' => [
                    'Required' => ModTypeEnum::REQUIRED,
                    'Optional' => ModTypeEnum::OPTIONAL,
                ],
                'empty_data' => ModTypeEnum::REQUIRED,
            ])
            ->add('source', ChoiceType::class, [
                'label' => 'Source',
                'choices' => [
                    'Steam Workshop' => ModSourceEnum::STEAM_WORKSHOP,
                    'Directory' => ModSourceEnum::DIRECTORY,
                ],
                'empty_data' => ModSourceEnum::STEAM_WORKSHOP,
            ])
            ->add('path', TextType::class, [
                'label' => 'URL or mod directory path',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModFormDto::class,
            'required' => false,
            'validation_groups' => function (FormInterface $form) {
                /** @var ModFormDto $modFormDto */
                $modFormDto = $form->getData();

                return $modFormDto->resolveValidationGroups();
            },
        ]);
    }
}
