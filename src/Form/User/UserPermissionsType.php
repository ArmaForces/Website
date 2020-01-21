<?php

declare(strict_types=1);

namespace App\Form\User;

use App\Entity\Permissions\Permissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserPermissionsType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('managePermissions', CheckboxType::class, [
                'label' => 'Can manage permissions',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('deleteUsers', CheckboxType::class, [
                'label' => 'Can delete users',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('submit', SubmitType::class, [
                'label' => 'Apply',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Permissions::class,
            'required' => false,
        ]);
    }
}
