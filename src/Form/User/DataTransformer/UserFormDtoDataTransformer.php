<?php

declare(strict_types=1);

namespace App\Form\User\DataTransformer;

use App\Entity\AbstractEntity;
use App\Entity\User\User;
use App\Form\FormDtoInterface;
use App\Form\RegisteredDataTransformerInterface;
use App\Form\User\Dto\UserFormDto;

class UserFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param UserFormDto $formDto
     * @param null|User   $entity
     *
     * @return User
     */
    public function transformToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): AbstractEntity
    {
        $entity->update(
            $entity->getUsername(),
            $entity->getEmail(),
            $entity->getExternalId(),
            $formDto->getPermissions(),
            $entity->getUserGroups(),
            $entity->getAvatarHash(),
            $formDto->getSteamId(),
        );

        return $entity;
    }

    /**
     * @param UserFormDto $formDto
     * @param null|User   $entity
     *
     * @return UserFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): FormDtoInterface
    {
        $formDto->setId($entity?->getId());
        $formDto->setSteamId($entity?->getSteamId());
        $formDto->setPermissions($entity?->getPermissions());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof UserFormDto && $entity instanceof User;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, AbstractEntity $entity = null): bool
    {
        return $formDto instanceof UserFormDto && $entity instanceof User;
    }
}
