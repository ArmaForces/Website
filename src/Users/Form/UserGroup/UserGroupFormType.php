<?php

declare(strict_types=1);

namespace App\Users\Form\UserGroup;

use App\Users\Entity\Permissions\UserGroupPermissions;
use App\Users\Entity\User\User;
use App\Users\Form\Permissions\PermissionsType;
use App\Users\Form\UserGroup\Dto\UserGroupFormDto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserGroupFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'User group name',
            ])
            ->add('description', TextType::class, [
                'label' => 'User group description',
            ])
            ->add('permissions', PermissionsType::class, [
                'label' => 'User group permissions',
                'data_class' => UserGroupPermissions::class,
                'target' => null,
            ])
            ->add('users', EntityType::class, [
                'label' => 'Users',
                'label_attr' => ['class' => 'switch-custom'],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => User::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->orderBy('u.username', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => UserGroupFormDto::class,
            'required' => false,
        ]);
    }
}
