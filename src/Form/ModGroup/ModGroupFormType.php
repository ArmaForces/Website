<?php

declare(strict_types=1);

namespace App\Form\ModGroup;

use App\Entity\Mod\AbstractMod;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModGroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod group name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod group description',
            ])
            ->add('mods', EntityType::class, [
                'label' => 'Mods',
                'label_attr' => ['class' => 'switch-custom'],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => AbstractMod::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('m')
                        ->orderBy('m.name', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModGroupFormDto::class,
            'required' => false,
        ]);
    }
}
