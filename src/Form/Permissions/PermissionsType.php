<?php

declare(strict_types=1);

namespace App\Form\Permissions;

use App\Entity\Permissions\Permissions;
use App\Form\Permissions\ModLists\ModListsPermissionsType;
use App\Form\Permissions\Mods\ModsPermissionsType;
use App\Form\Permissions\Users\UsersPermissionsType;
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
            ->add('usersPermissions', UsersPermissionsType::class, [
                'label' => 'Users',
                'relatedUser' => $relatedUser,
            ])
            ->add('modsPermissions', ModsPermissionsType::class, [
                'label' => 'Mods',
            ])
            ->add('modListsPermissions', ModListsPermissionsType::class, [
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
                'data_class' => Permissions::class,
                'required' => false,
            ])
            ->setRequired([
                'relatedUser',
            ])
        ;
    }
}
