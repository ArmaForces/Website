<?php

declare(strict_types=1);

namespace App\UserManagement\UserInterface\Http\Form\User\DataTransformer;

use App\SharedKernel\Domain\Model\EntityInterface;
use App\SharedKernel\UserInterface\Http\Form\FormDtoInterface;
use App\SharedKernel\UserInterface\Http\Form\RegisteredDataTransformerInterface;
use App\UserManagement\Domain\Model\User\UserInterface;
use App\UserManagement\UserInterface\Http\Form\User\Dto\UserFormDto;

class UserFormDtoDataTransformer implements RegisteredDataTransformerInterface
{
    /**
     * @param UserFormDto        $formDto
     * @param null|UserInterface $entity
     *
     * @return UserInterface
     */
    public function transformToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): EntityInterface
    {
        $entity?->setSteamId((int) $formDto->getSteamId());
        $entity?->setPermissions($formDto->getPermissions());

        return $entity;
    }

    /**
     * @param UserFormDto        $formDto
     * @param null|UserInterface $entity
     *
     * @return UserFormDto
     */
    public function transformFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): FormDtoInterface
    {
        $formDto->setId($entity?->getId());
        $steamId = $entity?->getSteamId();
        $steamId = $steamId ? (string) $steamId : null;
        $formDto->setSteamId($steamId);
        $formDto->setPermissions($entity?->getPermissions());

        return $formDto;
    }

    public function supportsTransformationToEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof UserFormDto && $entity instanceof UserInterface;
    }

    public function supportsTransformationFromEntity(FormDtoInterface $formDto, EntityInterface $entity = null): bool
    {
        return $formDto instanceof UserFormDto && $entity instanceof UserInterface;
    }
}
