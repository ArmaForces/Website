<?php

declare(strict_types=1);

namespace App\Form\Mod;

use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
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
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod description',
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Mod type',
                'choices' => [
                    'Server side mod' => ModTypeEnum::SERVER_SIDE,
                    'Required mod' => ModTypeEnum::REQUIRED,
                    'Optional mod' => ModTypeEnum::OPTIONAL,
                    'Client side mod' => ModTypeEnum::CLIENT_SIDE,
                ],
                'empty_data' => ModTypeEnum::REQUIRED,
            ])
            ->add('source', ChoiceType::class, [
                'label' => 'Mod source',
                'choices' => [
                    'Steam Workshop' => ModSourceEnum::STEAM_WORKSHOP,
                    'Directory' => ModSourceEnum::DIRECTORY,
                ],
                'empty_data' => ModSourceEnum::STEAM_WORKSHOP,
            ])
            ->add('url', TextType::class, [
                'label' => 'Steam Workshop URL',
            ])
            ->add('path', TextType::class, [
                'label' => 'Directory path',
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
