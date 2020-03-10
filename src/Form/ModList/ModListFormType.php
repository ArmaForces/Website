<?php

declare(strict_types=1);

namespace App\Form\ModList;

use App\Entity\Mod\SteamWorkshopMod;
use App\Form\ModList\Dto\ModListFormDto;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListFormType extends AbstractType
{
    /**
     * @param string[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod list name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod list description',
            ])
            ->add('steamWorkshopMods', EntityType::class, [
                'label' => 'Mods',
                'multiple' => true,
                'expanded' => true,
                'class' => SteamWorkshopMod::class,
                'choice_label' => static function (SteamWorkshopMod $steamWorkshopMod) {
                    return $steamWorkshopMod->getName();
                },
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModListFormDto::class,
            'required' => false,
        ]);
    }
}
