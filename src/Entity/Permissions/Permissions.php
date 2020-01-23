<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;

class Permissions extends AbstractEntity
{
    /** @var bool */
    protected $manageUsersPermissions = false;

    /** @var bool */
    protected $listUsers = false;

    /** @var bool */
    protected $deleteUsers = false;

    public function canManageUsersPermissions(): bool
    {
        return $this->manageUsersPermissions;
    }

    public function setManageUsersPermissions(bool $manageUsersPermissions): void
    {
        $this->manageUsersPermissions = $manageUsersPermissions;
    }

    public function canListUsers(): bool
    {
        return $this->listUsers;
    }

    public function setListUsers(bool $listUsers): void
    {
        $this->listUsers = $listUsers;
    }

    public function canDeleteUsers(): bool
    {
        return $this->deleteUsers;
    }

    public function setDeleteUsers(bool $deleteUsers): void
    {
        $this->deleteUsers = $deleteUsers;
    }
}
