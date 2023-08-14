<?php

declare(strict_types=1);

namespace App\Form\ModList\DataTransformer;

use App\Entity\AbstractEntity;
use App\Entity\ModList\ModList;
use App\Entity\Permissions\AbstractPermissions;
use App\Entity\User\User;
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
     * @param ModListFormDto $formDto
     * @param null|ModList   $entity
     *
     * @return ModList
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();

        $canUpdate = $currentUser->hasPermissions(
            static fn (AbstractPermissions $permissions) => $permissions->modListUpdate
        );

        // If user has permissions set selected user as owner. Otherwise assign current user.
        $owner = $canUpdate ? $formDto->getOwner() : $currentUser;

        if (!$entity instanceof ModList) {
            return new ModList(
                Uuid::uuid4(),
                $formDto->getName(),
                $formDto->getDescription(),
                $formDto->getMods(),
                $formDto->getModGroups(),
                $formDto->getDlcs(),
                $owner,
                $formDto->isActive(),
                $formDto->isApproved(),
            );
        }

        $entity->update(
            $formDto->getName(),
            $formDto->getDescription(),
            $formDto->getMods(),
            $formDto->getModGroups(),
            $formDto->getDlcs(),
            $owner,
            $formDto->isActive(),
            $formDto->isApproved(),
        );

        return $entity;
    }

    /**
     * @param ModListFormDto $formDto
     * @param null|ModList   $entity
     *
     * @return ModListFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        if (!$entity instanceof ModList) {
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

    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof ModListFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof ModListFormDto;
    }
}
