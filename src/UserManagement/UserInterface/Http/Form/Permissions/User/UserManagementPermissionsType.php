<?php

declare(strict_types=1);

namespace App\UserManagement\UserInterface\Http\Form\Permissions\User;

use App\UserManagement\Domain\Model\Permissions\User\UserManagementPermissions;
use App\UserManagement\Domain\Model\User\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class UserManagementPermissionsType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        /** @var null|User $target */
        $target = $options['target'];

        $builder
            ->add('list', CheckboxType::class, [
                'label' => 'Can list users',
                'label_attr' => ['class' => 'switch-custom'],
                // User cannot change his own base permissions
                'disabled' => $currentUser === $target,
            ])
            ->add('update', CheckboxType::class, [
                'label' => 'Can edit users',
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
