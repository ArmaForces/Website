<?php

declare(strict_types=1);

namespace App\Form\ModList\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use App\Entity\Permissions\PermissionsInterface;
use App\Entity\User\UserInterface;
use App\Form\FormDtoInterface;
use App\Form\ModList\Dto\ModListFormDto;
use App\Form\RegisteredDataTransformerInterface;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Security\Core\Security;

class ModListFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    public function __construct(
        private Security $security
    ) {
    }

    /**
     * @param ModListFormDto        $formDto
     * @param null|ModListInterface $entity
     *
     * @return ModListInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        if (!$entity instanceof ModListInterface) {
            $entity = new ModList(Uuid::uuid4(), $formDto->getName());
        }

        /** @var UserInterface $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (PermissionsInterface $permissions) => $permissions->getModListManagementPermissions()->canUpdate()
        );

        // If user has permissions set selected user as owner. Otherwise assign current user.
        $owner = $canUpdate ? $formDto->getOwner() : $currentUser;

        $entity->setName($formDto->getName());
        $entity->setDescription($formDto->getDescription());
        $entity->setMods($formDto->getMods());
        $entity->setModGroups($formDto->getModGroups());
        $entity->setDlcs($formDto->getDlcs());
        $entity->setOwner($owner);
        $entity->setActive($formDto->isActive());
        $entity->setApproved($formDto->isApproved());

        return $entity;
    }

    /**
     * @param ModListFormDto        $formDto
     * @param null|ModListInterface $entity
     *
     * @return ModListFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        /** @var ModInterface $entity */
        if (!$entity instanceof ModListInterface) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $formDto->setMods($entity->getMods());
        $formDto->setModGroups($entity->getModGroups());
        $formDto->setDlcs($entity->getDlcs());
        $formDto->setOwner($entity->getOwner());
        $formDto->setActive($entity->isActive());
        $formDto->setApproved($entity->isApproved());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModListFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof ModListFormDto;
    }
}
