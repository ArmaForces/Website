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
                'label' => 'Can edit other users mod lists',
                'label_attr' => ['class' => 'switch-custom'],
                'help' => 'User can always edit his own mod lists',
            ])
            ->add('copy', CheckboxType::class, [
                'label' => 'Can copy mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('delete', CheckboxType::class, [
                'label' => 'Can delete other users mod lists',
                'label_attr' => ['class' => 'switch-custom'],
                'help' => 'User can always delete his own mod lists',
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
