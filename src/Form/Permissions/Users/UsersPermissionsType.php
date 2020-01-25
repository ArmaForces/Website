<?php

declare(strict_types=1);

namespace App\Form\Permissions\Users;

use App\Entity\Permissions\Users\UsersPermissions;
use App\Entity\User\UserEntity;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;

class UsersPermissionsType extends AbstractType
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
            ->add('managePermissions', CheckboxType::class, [
                'label' => 'Can manage users permissions',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser->getId() === $relatedUser->getId(),
            ])
            ->add('list', CheckboxType::class, [
                'label' => 'Can list users',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser->getId() === $relatedUser->getId(),
            ])
            ->add('delete', CheckboxType::class, [
                'label' => 'Can delete users',
                'label_attr' => ['class' => 'switch-custom'],
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
                'data_class' => UsersPermissions::class,
                'required' => false,
            ])
            ->setRequired([
                'relatedUser',
            ])
        ;
    }
}
