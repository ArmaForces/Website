<?php

declare(strict_types=1);

namespace App\Form\Permissions\ModList;

use App\Entity\Permissions\ModList\ModListPermissions;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListPermissionsType extends AbstractType
{
    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('list', CheckboxType::class, [
                'label' => 'Can list mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('create', CheckboxType::class, [
                'label' => 'Can create mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('update', CheckboxType::class, [
                'label' => 'Can edit mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('delete', CheckboxType::class, [
                'label' => 'Can delete mod lists',
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
            'data_class' => ModListPermissions::class,
            'required' => false,
        ]);
    }
}
