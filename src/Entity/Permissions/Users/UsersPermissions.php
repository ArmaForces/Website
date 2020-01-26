<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Users;

class UsersPermissions
{
    /** @var bool */
    protected $managePermissions = false;

    /** @var bool */
    protected $list = false;

    /** @var bool */
    protected $delete = false;

    public function canManagePermissions(): bool
    {
        return $this->managePermissions;
    }

    public function setManagePermissions(bool $managePermissions): void
    {
        $this->managePermissions = $managePermissions;
    }

    public function canList(): bool
    {
        return $this->list;
    }

    public function setList(bool $list): void
    {
        $this->list = $list;
    }

    public function canDelete(): bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): void
    {
        $this->delete = $delete;
    }
}
