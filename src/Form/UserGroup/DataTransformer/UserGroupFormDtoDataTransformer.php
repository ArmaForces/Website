<?php

declare(strict_types=1);

namespace App\Form\UserGroup\DataTransformer;

use App\Entity\EntityInterface;
use App\Entity\UserGroup\UserGroup;
use App\Entity\UserGroup\UserGroupInterface;
use App\Form\FormDtoInterface;
use App\Form\RegisteredDataTransformerInterface;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use Ramsey\Uuid\Uuid;

class UserGroupFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param UserGroupFormDto        $formDto
     * @param null|UserGroupInterface $entity
     *
     * @return UserGroupInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        if (!$entity instanceof UserGroupInterface) {
            $entity = new UserGroup(Uuid::uuid4(), $formDto->getName(), $formDto->getPermissions());
        }

        $entity->setName($formDto->getName());
        $entity->setDescription($formDto->getDescription());
        $entity->setPermissions($formDto->getPermissions());
        $entity->setUsers($formDto->getUsers());

        return $entity;
    }

    /**
     * @param UserGroupFormDto        $formDto
     * @param null|UserGroupInterface $entity
     *
     * @return UserGroupFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        /** @var UserGroupInterface $entity */
        if (!$entity instanceof UserGroupInterface) {
            return $formDto;
        }

        $formDto->setId($entity->getId());
        $formDto->setName($entity->getName());
        $formDto->setDescription($entity->getDescription());
        $formDto->setPermissions($entity->getPermissions());
        $formDto->setUsers($entity->getUsers());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof UserGroupFormDto;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof UserGroupFormDto;
    }
}
