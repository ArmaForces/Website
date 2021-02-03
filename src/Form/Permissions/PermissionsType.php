<?php

declare(strict_types=1);

namespace App\Form\Permissions;

use App\Entity\Permissions\UserPermissions;
use App\Form\Permissions\Mod\ModManagementPermissionsType;
use App\Form\Permissions\ModGroup\ModGroupManagementPermissionsType;
use App\Form\Permissions\ModList\ModListManagementPermissionsType;
use App\Form\Permissions\ModTag\ModTagManagementPermissionsType;
use App\Form\Permissions\User\UserManagementPermissionsType;
use App\Form\Permissions\UserGroup\UserGroupManagementPermissionsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionsType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $target = $options['target'];

        $builder
            ->add('userManagementPermissions', UserManagementPermissionsType::class, [
                'label' => 'Users',
                'target' => $target,
            ])
            ->add('userGroupManagementPermissions', UserGroupManagementPermissionsType::class, [
                'label' => 'User groups',
            ])
            ->add('modManagementPermissions', ModManagementPermissionsType::class, [
                'label' => 'Mods',
            ])
            ->add('modTagManagementPermissions', ModTagManagementPermissionsType::class, [
                'label' => 'Mod tags',
            ])
            ->add('modGroupManagementPermissions', ModGroupManagementPermissionsType::class, [
                'label' => 'Mod groups',
            ])
            ->add('modListManagementPermissions', ModListManagementPermissionsType::class, [
                'label' => 'Mod lists',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => UserPermissions::class,
                'required' => false,
                'target' => null,
            ])
        ;
    }
}
