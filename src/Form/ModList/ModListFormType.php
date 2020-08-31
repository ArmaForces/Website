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
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class ModListFormType extends AbstractType
{
    /** @var Security */
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();

        /** @var ModListFormDto $modListFormDto */
        $modListFormDto = $builder->getData();
        $modListExists = null !== $modListFormDto->getId();

        $builder
            ->add('name', TextType::class, [
                'label' => 'Mod list name',
            ])
            ->add('description', TextType::class, [
                'label' => 'Mod list description',
            ])
        ;

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
            'choice_label' => static function (UserInterface $user) {
                return $user->getUsername();
            },
        ];

        if (!$modListExists) {
            // Set current user as default owner when creating new Mod List
            $ownerTypeConfig['data'] = $currentUser;
        }

        // Add owner list only if user has full permissions to edit Mod Lists
        if ($currentUser->getPermissions()->getModListPermissions()->canUpdate()) {
            $builder->add('owner', EntityType::class, $ownerTypeConfig);
        }

        $builder
            ->add('active', CheckboxType::class, [
                'label' => 'Mod list active',
                'required' => false,
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
