<?php

declare(strict_types=1);

namespace App\Form\Permissions\User;

use App\Entity\Permissions\User\UserManagementPermissions;
use App\Entity\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserManagementPermissionsType extends AbstractType
{
    protected Security $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @param mixed[] $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        /** @var null|User $target */
        $target = $options['target'];

        $builder
            ->add('managePermissions', CheckboxType::class, [
                'label' => 'Can manage users permissions',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser === $target,
            ])
            ->add('list', CheckboxType::class, [
                'label' => 'Can list users',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser === $target,
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
                'data_class' => UserManagementPermissions::class,
                'required' => false,
            ])
            ->setRequired([
                'target',
            ])
        ;
    }
}
