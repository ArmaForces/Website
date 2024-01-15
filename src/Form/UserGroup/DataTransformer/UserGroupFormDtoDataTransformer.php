<?php

declare(strict_types=1);

namespace App\Form\UserGroup\DataTransformer;

use App\Entity\UserGroup\UserGroup;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use Ramsey\Uuid\Uuid;

class UserGroupFormDtoDataTransformer
{
    public function transformToEntity(UserGroupFormDto $userGroupFormDto, UserGroup $userGroup = null): UserGroup
    {
        if (!$userGroup instanceof UserGroup) {
            return new UserGroup(
                Uuid::uuid4(),
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
