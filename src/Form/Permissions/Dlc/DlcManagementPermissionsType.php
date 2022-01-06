<?php

declare(strict_types=1);

namespace App\Form\Permissions\Dlc;

use App\Entity\Permissions\Dlc\DlcManagementPermissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DlcManagementPermissionsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('list', CheckboxType::class, [
                'label' => 'Can list DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('create', CheckboxType::class, [
                'label' => 'Can create DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('update', CheckboxType::class, [
                'label' => 'Can edit DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('delete', CheckboxType::class, [
                'label' => 'Can delete DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DlcManagementPermissions::class,
            'required' => false,
        ]);
    }
}
