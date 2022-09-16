<?php

declare(strict_types=1);

namespace App\Form\ModList;

use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\User;
use App\Entity\User\UserInterface;
use App\Form\ModList\Dto\ModListFormDtoHtml;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ModListFormTypeHtml extends AbstractType
{
    public function __construct(
        private Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('attachment', FileType::class, [
                'label' => 'HTML file',
                'attr' => [
                    'placeholder' => 'Choose a file',
                ],
            ])
        ;

        $this->addOwnerType($builder);

        $builder
            ->add('active', CheckboxType::class, [
                'label' => 'Mod list active',
                'required' => false,
            ])
        ;

        $this->addApprovedType($builder);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModListFormDtoHtml::class,
            'required' => false,
        ]);
    }

    protected function addOwnerType(FormBuilderInterface $builder): void
    {
        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canUpdate()
        );

        // User cannot change Mod List owner if he doesn't have full update permissions granted
        if (!$canUpdate) {
            return;
        }

        $ownerTypeConfig = [
            'label' => 'Mod list owner',
            'required' => false,
            'class' => User::class,
            'query_builder' => static function (EntityRepository $er) {
                return $er->createQueryBuilder('u')
                    ->join('u.permissions', 'p')
                    ->orderBy('u.username', 'ASC')
                ;
            },
            'choice_label' => static fn (UserInterface $user) => $user->getUsername(),
        ];

        // Set current user as default owner when creating new Mod List
        $ownerTypeConfig['data'] = $currentUser;

        $builder->add('owner', EntityType::class, $ownerTypeConfig);
    }

    protected function addApprovedType(FormBuilderInterface $builder): void
    {
        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();

        $canApprove = $currentUser->hasPermissions(
            static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canApprove()
        );

        if (!$canApprove) {
            return;
        }

        $builder
            ->add('approved', CheckboxType::class, [
                'label' => 'Mod list approved',
                'required' => false,
            ])
        ;
    }
}
