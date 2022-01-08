<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions\User;

use App\UserManagement\Domain\Model\Permissions\Traits\DeletePermissionTrait;
use App\UserManagement\Domain\Model\Permissions\Traits\ListPermissionTrait;
use App\UserManagement\Domain\Model\Permissions\Traits\UpdatePermissionTrait;

class UserManagementPermissions
{
    use ListPermissionTrait;
    use UpdatePermissionTrait;
    use DeletePermissionTrait;
}
