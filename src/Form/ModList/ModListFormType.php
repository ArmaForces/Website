<?php

declare(strict_types=1);

namespace App\Form\ModList;

use App\Entity\Mod\AbstractMod;
use App\Entity\User\User;
use App\Entity\User\UserInterface;
use App\Form\ModList\Dto\ModListFormDto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListFormType extends AbstractType
{
    /**
     * {@inheritdoc}
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
            ->add('createdBy', EntityType::class, [
                'label' => 'Mod list owner',
                'required' => false,
                'class' => User::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->join('u.permissions', 'p')
                        ->orderBy('u.username', 'ASC')
                    ;
                },
                'choice_label' => static function (UserInterface $user) {
                    return $user->getUsername();
                },
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
