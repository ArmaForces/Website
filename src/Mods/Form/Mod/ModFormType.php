<?php

declare(strict_types=1);

namespace App\Mods\Form\Mod;

use App\Mods\Entity\Mod\Enum\ModSourceEnum;
use App\Mods\Entity\Mod\Enum\ModStatusEnum;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Form\Mod\Dto\ModFormDto;
use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModFormType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var ModFormDto $modFormDto */
        $modFormDto = $options['data'];
        $modExists = null !== $modFormDto->getId();

        $builder
            ->add('source', ChoiceType::class, [
                'label' => 'Mod source',
                'disabled' => $modExists,
                'choices' => [
                    'Steam Workshop' => ModSourceEnum::STEAM_WORKSHOP->value,
                    'Directory' => ModSourceEnum::DIRECTORY->value,
                ],
                'empty_data' => ModSourceEnum::STEAM_WORKSHOP->value,
            ])
            ->add('url', TextType::class, [
                'label' => 'Steam Workshop URL',
                'attr' => [
                    'placeholder' => 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795',
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
                    'Required mod' => ModTypeEnum::REQUIRED->value,
                    'Server side mod' => ModTypeEnum::SERVER_SIDE->value,
                    'Client side mod' => ModTypeEnum::CLIENT_SIDE->value,
                    'Optional mod' => ModTypeEnum::OPTIONAL->value,
                ],
                'empty_data' => ModTypeEnum::REQUIRED->value,
            ])
        ;

        $this->addChangeStatusType($builder);

        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod name',
                'help' => 'Optional for mods from Steam Workshop',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod description',
            ])
        ;
    }

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

    private function addChangeStatusType(FormBuilderInterface $builder): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $canChangeStatus = $currentUser->hasPermissions(
            static fn (AbstractPermissions $permissions) => $permissions->modChangeStatus
        );

        if (!$canChangeStatus) {
            return;
        }

        $builder
            ->add('status', ChoiceType::class, [
                'label' => 'Mod status',
                'required' => false,
                'choices' => [
                    'Deprecated' => ModStatusEnum::DEPRECATED->value,
                    'Broken' => ModStatusEnum::BROKEN->value,
                    'Disabled' => ModStatusEnum::DISABLED->value,
                ],
            ])
        ;
    }
}
