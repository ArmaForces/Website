<?php

declare(strict_types=1);

namespace App\Entity\UserGroup;

use App\Entity\DescribedEntityInterface;
use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\User\UserInterface;

interface UserGroupInterface extends DescribedEntityInterface
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
