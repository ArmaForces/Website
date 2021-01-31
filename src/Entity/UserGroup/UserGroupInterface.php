<?php

declare(strict_types=1);

namespace App\Entity\UserGroup;

use App\Entity\DescribedEntityInterface;
use App\Entity\Permissions\Permissions;
use App\Entity\User\UserInterface;

interface UserGroupInterface extends DescribedEntityInterface
{
    public function getPermissions(): Permissions;

    public function setPermissions(Permissions $permissions): void;

    public function addUser(UserInterface $user): void;

    public function removeUser(UserInterface $user): void;

    public function getUsers(): array;

    public function setUsers(array $users): void;
}
