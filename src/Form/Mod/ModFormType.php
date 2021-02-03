<?php

declare(strict_types=1);

namespace App\Form\Mod;

use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\ModTag\ModTag;
use App\Entity\User\UserInterface;
use App\Form\Mod\Dto\ModFormDto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ModFormType extends AbstractType
{
    /** @var Security */
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
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
                'attr' => [
                    'placeholder' => 'https://steamcommunity.com/workshop/filedetails/?id=',
                ],
            ])
            ->add('directory', TextType::class, [
                'label' => 'Mod directory',
                'attr' => [
                    'placeholder' => '@R3',
                ],
                'row_attr' => [
                    'class' => 'd-none',
                ],
            ])
            ->add('type', ChoiceType::class, [
                'label' => 'Mod type',
                'choices' => [
                    'Required mod' => ModTypeEnum::REQUIRED,
                    'Server side mod' => ModTypeEnum::SERVER_SIDE,
                    'Client side mod' => ModTypeEnum::CLIENT_SIDE,
                    'Optional mod' => ModTypeEnum::OPTIONAL,
                ],
                'empty_data' => ModTypeEnum::REQUIRED,
            ])
        ;

        $this->addChangeStatusType($builder);

        $builder
            ->add('tags', EntityType::class, [
                'label' => 'Mod tags',
                'multiple' => true,
                'expanded' => false,
                'class' => ModTag::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('mt')
                        ->orderBy('mt.name', 'ASC')
                    ;
                },
                'choice_label' => function (ModTag $tag) {
                    return $tag->getName();
                },
            ])
            ->add('name', TextType::class, [
                'label' => 'Mod name',
                'help' => 'Optional for mods from Steam Workshop',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod description',
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

    protected function addChangeStatusType(FormBuilderInterface $builder): void
    {
        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();

        if (!$currentUser->getPermissions()->getModManagementPermissions()->canChangeStatus()) {
            return;
        }

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Mod status',
                'required' => false,
                'choices' => [
                    'Deprecated' => ModStatusEnum::DEPRECATED,
                    'Broken' => ModStatusEnum::BROKEN,
                    'Disabled' => ModStatusEnum::DISABLED,
                ],
            ])
        ;
    }
}
