<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions\Mod;

use App\UserManagement\Domain\Model\Permissions\AbstractManagementPermissions;

class ModManagementPermissions extends AbstractManagementPermissions
{
    private bool $changeStatus = false;

    public function canChangeStatus(): bool
    {
        return $this->changeStatus;
    }

    public function setChangeStatus(bool $changeStatus): void
    {
        $this->changeStatus = $changeStatus;
    }
}
