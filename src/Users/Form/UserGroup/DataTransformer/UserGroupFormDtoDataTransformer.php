<?php

declare(strict_types=1);

namespace App\Users\Form\UserGroup\DataTransformer;

use App\Shared\Service\IdentifierFactory\IdentifierFactoryInterface;
use App\Users\Entity\Permissions\UserGroupPermissions;
use App\Users\Entity\UserGroup\UserGroup;
use App\Users\Form\UserGroup\Dto\UserGroupFormDto;

class UserGroupFormDtoDataTransformer
{
    public function __construct(
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function transformToEntity(UserGroupFormDto $userGroupFormDto, UserGroup $userGroup = null): UserGroup
    {
        if (!$userGroup instanceof UserGroup) {
            return new UserGroup(
                $this->identifierFactory->create(),
                $userGroupFormDto->getName(),
                $userGroupFormDto->getDescription(),
                $userGroupFormDto->getPermissions(),
                $userGroupFormDto->getUsers()
            );
        }

        $userGroup->update(
            $userGroupFormDto->getName(),
            $userGroupFormDto->getDescription(),
            $userGroupFormDto->getPermissions(),
            $userGroupFormDto->getUsers()
        );

        return $userGroup;
    }

    public function transformFromEntity(UserGroupFormDto $userGroupFormDto, UserGroup $userGroup = null): UserGroupFormDto
    {
        if (!$userGroup instanceof UserGroup) {
            $permissions = new UserGroupPermissions($this->identifierFactory->create());
            $userGroupFormDto->setPermissions($permissions);

            return $userGroupFormDto;
        }

        $userGroupFormDto->setId($userGroup->getId());
        $userGroupFormDto->setName($userGroup->getName());
        $userGroupFormDto->setDescription($userGroup->getDescription());
        $userGroupFormDto->setPermissions($userGroup->getPermissions());
        $userGroupFormDto->setUsers($userGroup->getUsers());

        return $userGroupFormDto;
    }
}
