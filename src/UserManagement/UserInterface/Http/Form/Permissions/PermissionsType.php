<?php

declare(strict_types=1);

namespace App\UserManagement\UserInterface\Http\Form\Permissions;

use App\UserManagement\Domain\Model\Permissions\UserPermissions;
use App\UserManagement\UserInterface\Http\Form\Permissions\Dlc\DlcManagementPermissionsType;
use App\UserManagement\UserInterface\Http\Form\Permissions\Mod\ModManagementPermissionsType;
use App\UserManagement\UserInterface\Http\Form\Permissions\ModGroup\ModGroupManagementPermissionsType;
use App\UserManagement\UserInterface\Http\Form\Permissions\ModList\ModListManagementPermissionsType;
use App\UserManagement\UserInterface\Http\Form\Permissions\User\UserManagementPermissionsType;
use App\UserManagement\UserInterface\Http\Form\Permissions\UserGroup\UserGroupManagementPermissionsType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionsType extends AbstractType
{
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
            ->add('modGroupManagementPermissions', ModGroupManagementPermissionsType::class, [
                'label' => 'Mod groups',
            ])
            ->add('dlcManagementPermissions', DlcManagementPermissionsType::class, [
                'label' => 'DLCs',
            ])
            ->add('modListManagementPermissions', ModListManagementPermissionsType::class, [
                'label' => 'Mod lists',
            ])
        ;
    }

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
