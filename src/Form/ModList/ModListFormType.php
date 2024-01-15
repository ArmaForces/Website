<?php

declare(strict_types=1);

namespace App\Form\ModList;

use App\Entity\Dlc\Dlc;
use App\Entity\Mod\AbstractMod;
use App\Entity\ModGroup\ModGroup;
use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\User;
use App\Form\ModList\Dto\ModListFormDto;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ModListFormType extends AbstractType
{
    public function __construct(
        private Security $security
    ) {
    }

    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod list name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod list description',
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

        $builder
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
            ->add('modGroups', EntityType::class, [
                'label' => 'Mod groups',
                'label_attr' => ['class' => 'switch-custom'],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => ModGroup::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('mg')
                        ->orderBy('mg.name', 'ASC')
                    ;
                },
            ])
            ->add('dlcs', EntityType::class, [
                'label' => 'DLCs',
                'label_attr' => ['class' => 'switch-custom'],
                'choice_label' => false,
                'multiple' => true,
                'expanded' => true,
                'class' => Dlc::class,
                'query_builder' => static function (EntityRepository $er) {
                    return $er->createQueryBuilder('d')
                        ->orderBy('d.name', 'ASC')
                    ;
                },
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => ModListFormDto::class,
            'required' => false,
        ]);
    }

    private function addOwnerType(FormBuilderInterface $builder): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (AbstractPermissions $permissions) => $permissions->modListUpdate
        );

        // User cannot change Mod List owner if he doesn't have full update permissions granted
        if (!$canUpdate) {
            return;
        }

        /** @var ModListFormDto $modListFormDto */
        $modListFormDto = $builder->getData();
        $modListExists = null !== $modListFormDto->getId();

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
            'choice_label' => static fn (User $user) => $user->getUsername(),
        ];

        if (!$modListExists) {
            // Set current user as default owner when creating new Mod List
            $ownerTypeConfig['data'] = $currentUser;
        }

        $builder->add('owner', EntityType::class, $ownerTypeConfig);
    }

    private function addApprovedType(FormBuilderInterface $builder): void
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $canApprove = $currentUser->hasPermissions(
            static fn (AbstractPermissions $permissions) => $permissions->modListApprove
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
