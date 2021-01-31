<?php

declare(strict_types=1);

namespace App\Entity\Permissions\User;

use App\Entity\Permissions\Traits\DeletePermissionTrait;
use App\Entity\Permissions\Traits\ListPermissionTrait;

class UserManagementPermissions
{
    use ListPermissionTrait;
    use DeletePermissionTrait;

    /** @var bool */
    protected $managePermissions = false;

    public function canManagePermissions(): bool
    {
        return $this->managePermissions;
    }

    public function setManagePermissions(bool $managePermissions): void
    {
        $this->managePermissions = $managePermissions;
    }
}
