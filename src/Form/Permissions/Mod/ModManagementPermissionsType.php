<?php

declare(strict_types=1);

namespace App\Form\Permissions\Mod;

use App\Entity\Permissions\Mod\ModManagementPermissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModManagementPermissionsType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('list', CheckboxType::class, [
                'label' => 'Can list mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('create', CheckboxType::class, [
                'label' => 'Can create mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('update', CheckboxType::class, [
                'label' => 'Can edit mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('delete', CheckboxType::class, [
                'label' => 'Can delete mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('changeStatus', CheckboxType::class, [
                'label' => 'Can change mods status',
                'label_attr' => ['class' => 'switch-custom'],
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModManagementPermissions::class,
            'required' => false,
        ]);
    }
}
