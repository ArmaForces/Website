<?php

declare(strict_types=1);

namespace App\Form\Permissions;

use App\Entity\Permissions\Permissions;
use App\Form\Permissions\Mod\ModPermissionsType;
use App\Form\Permissions\ModList\ModListPermissionsType;
use App\Form\Permissions\User\UserPermissionsType;
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
            ->add('userPermissions', UserPermissionsType::class, [
                'label' => 'Users',
                'relatedUser' => $relatedUser,
            ])
            ->add('modPermissions', ModPermissionsType::class, [
                'label' => 'Mods',
            ])
            ->add('modListPermissions', ModListPermissionsType::class, [
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
