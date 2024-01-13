<?php

declare(strict_types=1);

namespace App\Form\User\DataTransformer;

use App\Entity\User\User;
use App\Form\User\Dto\UserFormDto;

class UserFormDtoDataTransformer
{
    public function transformToEntity(UserFormDto $userFormDto, User $user = null): User
    {
        $user->update(
            $user->getUsername(),
            $user->getEmail(),
            $user->getExternalId(),
            $userFormDto->getPermissions(),
            $user->getUserGroups(),
            $user->getAvatarHash(),
            $userFormDto->getSteamId(),
        );

        return $user;
    }

    public function transformFromEntity(UserFormDto $userFormDto, User $user = null): UserFormDto
    {
        $userFormDto->setId($user?->getId());
        $userFormDto->setSteamId($user?->getSteamId());
        $userFormDto->setPermissions($user?->getPermissions());

        return $userFormDto;
    }
}
