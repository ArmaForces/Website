<?php

declare(strict_types=1);

namespace App\Form\User;

use App\Entity\Permissions\Permissions;
use App\Entity\User\UserEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UserPermissionsType extends AbstractType
{
    /** @var TokenStorageInterface */
    protected $tokenStorage;

    public function __construct(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserEntity $currentUser */
        $currentUser = $this->tokenStorage->getToken()->getUser();
        $relatedUser = $options['relatedUser'];

        $builder
            ->add('manageUsersPermissions', CheckboxType::class, [
                'label' => 'Can manage users permissions',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser->getId() === $relatedUser->getId(),
            ])
            ->add('listUsers', CheckboxType::class, [
                'label' => 'Can list users',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser->getId() === $relatedUser->getId(),
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
        $resolver
            ->setDefaults([
                'data_class' => Permissions::class,
                'required' => false,
            ])
            ->setRequired([
                'relatedUser',
            ])
        ;
    }
}
