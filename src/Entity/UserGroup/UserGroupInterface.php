<?php

declare(strict_types=1);

namespace App\Entity\UserGroup;

use App\Entity\BlamableEntityInterface;
use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\Traits\DescribedInterface;
use App\Entity\Traits\NamedInterface;
use App\Entity\User\UserInterface;

interface UserGroupInterface extends BlamableEntityInterface, NamedInterface, DescribedInterface
{
    public function getPermissions(): UserGroupPermissions;

    public function setPermissions(UserGroupPermissions $permissions): void;

    public function addUser(UserInterface $user): void;

    public function removeUser(UserInterface $user): void;

    /**
     * @return UserInterface[]
     */
    public function getUsers(): array;

    public function setUsers(array $users): void;
}
