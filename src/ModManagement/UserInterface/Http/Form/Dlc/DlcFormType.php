<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Form\Dlc;

use App\ModManagement\UserInterface\Http\Form\Dlc\Dto\DlcFormDto;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DlcFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', TextType::class, [
                'label' => 'Steam Store URL',
                'attr' => [
                    'placeholder' => 'https://store.steampowered.com/app/1227700/Arma_3_Creator_DLC_SOG_Prairie_Fire',
                ],
            ])
            ->add('directory', TextType::class, [
                'label' => 'DLC directory',
            ])
            ->add('name', TextType::class, [
                'label' => 'DLC name',
            ])
            ->add('description', TextType::class, [
                'label' => 'DLC description',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => DlcFormDto::class,
            'required' => false,
        ]);
    }
}
