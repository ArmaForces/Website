<?php

declare(strict_types=1);

namespace App\Form\Permissions;

use App\Entity\Permissions\AbstractPermissions;
use App\Form\Permissions\Mod\ModManagementPermissionsType;
use App\Form\Permissions\ModGroup\ModGroupManagementPermissionsType;
use App\Form\Permissions\ModList\ModListManagementPermissionsType;
use App\Form\Permissions\User\UserManagementPermissionsType;
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
        $relatedUser = $options['relatedUser'];

        $builder
            ->add('userManagementPermissions', UserManagementPermissionsType::class, [
                'label' => 'Users',
                'relatedUser' => $relatedUser,
            ])
            ->add('modManagementPermissions', ModManagementPermissionsType::class, [
                'label' => 'Mods',
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
                'data_class' => AbstractPermissions::class,
                'required' => false,
            ])
            ->setRequired([
                'relatedUser',
            ])
        ;
    }
}
