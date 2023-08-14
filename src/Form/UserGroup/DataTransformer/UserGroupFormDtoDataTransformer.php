<?php

declare(strict_types=1);

namespace App\Form\UserGroup\DataTransformer;

use App\Entity\AbstractEntity;
use App\Entity\UserGroup\UserGroup;
use App\Form\FormDtoInterface;
use App\Form\RegisteredDataTransformerInterface;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use Ramsey\Uuid\Uuid;

class UserGroupFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param UserGroupFormDto $formDto
     * @param null|UserGroup   $entity
     *
     * @return UserGroup
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        if (!$entity instanceof UserGroup) {
            return new UserGroup(
                Uuid::uuid4(),
                $formDto->getName(),
                $formDto->getDescription(),
                $formDto->getPermissions(),
                $formDto->getUsers()
            );
        }

        $entity->update(
            $formDto->getName(),
            $formDto->getDescription(),
            $formDto->getPermissions(),
            $formDto->getUsers()
        );

        return $entity;
    }

    /**
     * @param UserGroupFormDto $formDto
     * @param null|UserGroup   $entity
     *
     * @return UserGroupFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        /** @var UserGroup $entity */
        if (!$entity instanceof UserGroup) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $formDto->setPermissions($entity->getPermissions());
        $formDto->setUsers($entity->getUsers());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof UserGroupFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof UserGroupFormDto;
    }
}
