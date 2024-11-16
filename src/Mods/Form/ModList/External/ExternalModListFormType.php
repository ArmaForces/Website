<?php

declare(strict_types=1);

namespace App\Mods\Form\ModList\External;

use App\Mods\Form\ModList\External\Dto\ExternalModListFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExternalModListFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod list name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod list description',
            ])
            ->add('url', TextType::class, [
                'label' => 'Mod list url',
            ])
            ->add('active', CheckboxType::class, [
                'label' => 'Mod list active',
                'required' => false,
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ExternalModListFormDto::class,
            'required' => false,
        ]);
    }
}
