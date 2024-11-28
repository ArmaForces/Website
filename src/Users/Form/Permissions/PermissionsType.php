<?php

declare(strict_types=1);

namespace App\Users\Form\Permissions;

use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\User\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PermissionsType extends AbstractType
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
            // User
            ->add('userList', CheckboxType::class, [
                'label' => 'Can list users',
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => $currentUser === $target, // User cannot change his own base permissions
            ])
            ->add('userUpdate', CheckboxType::class, [
                'label' => 'Can edit users',
                'label_attr' => ['class' => 'switch-custom'],
                'disabled' => $currentUser === $target, // User cannot change his own base permissions
            ])
            ->add('userDelete', CheckboxType::class, [
                'label' => 'Can delete users',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            // User Group
            ->add('userGroupList', CheckboxType::class, [
                'label' => 'Can list user groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('userGroupCreate', CheckboxType::class, [
                'label' => 'Can create user groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('userGroupUpdate', CheckboxType::class, [
                'label' => 'Can edit user groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('userGroupDelete', CheckboxType::class, [
                'label' => 'Can delete user groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            // Mod
            ->add('modList', CheckboxType::class, [
                'label' => 'Can list mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modCreate', CheckboxType::class, [
                'label' => 'Can create mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modUpdate', CheckboxType::class, [
                'label' => 'Can edit mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modDelete', CheckboxType::class, [
                'label' => 'Can delete mods',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modChangeStatus', CheckboxType::class, [
                'label' => 'Can change mods status',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            // Mod Group
            ->add('modGroupList', CheckboxType::class, [
                'label' => 'Can list mod groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modGroupCreate', CheckboxType::class, [
                'label' => 'Can create mod groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modGroupUpdate', CheckboxType::class, [
                'label' => 'Can edit mod groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('modGroupDelete', CheckboxType::class, [
                'label' => 'Can delete mod groups',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            // Dlc
            ->add('dlcList', CheckboxType::class, [
                'label' => 'Can list DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('dlcCreate', CheckboxType::class, [
                'label' => 'Can create DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('dlcUpdate', CheckboxType::class, [
                'label' => 'Can edit DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('dlcDelete', CheckboxType::class, [
                'label' => 'Can delete DLCs',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            // Mod List
            ->add('modListList', CheckboxType::class, [
                'label' => 'Can list mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            ->add('standardModListCreate', CheckboxType::class, [
                'label' => 'Can create mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('standardModListUpdate', CheckboxType::class, [
                'label' => 'Can edit other users mod lists',
                'label_attr' => ['class' => 'switch-custom'],
                'help' => 'Note: User can always edit his own or assigned to him mod lists',
            ])
            ->add('standardModListCopy', CheckboxType::class, [
                'label' => 'Can copy mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('standardModListDelete', CheckboxType::class, [
                'label' => 'Can delete other users mod lists',
                'label_attr' => ['class' => 'switch-custom'],
                'help' => 'Note: User can always delete his own or assigned to him mod lists',
            ])
            ->add('standardModListApprove', CheckboxType::class, [
                'label' => 'Can approve mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])

            ->add('externalModListCreate', CheckboxType::class, [
                'label' => 'Can create external mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('externalModListUpdate', CheckboxType::class, [
                'label' => 'Can edit external mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
            ->add('externalModListDelete', CheckboxType::class, [
                'label' => 'Can delete external mod lists',
                'label_attr' => ['class' => 'switch-custom'],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefaults([
                'data_class' => AbstractPermissions::class,
                'required' => false,
            ])
            ->setRequired([
                'target',
            ])
        ;
    }
}
