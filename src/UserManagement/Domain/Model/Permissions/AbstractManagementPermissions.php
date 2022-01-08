<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions;

use App\UserManagement\Domain\Model\Permissions\Traits\CreatePermissionTrait;
use App\UserManagement\Domain\Model\Permissions\Traits\DeletePermissionTrait;
use App\UserManagement\Domain\Model\Permissions\Traits\ListPermissionTrait;
use App\UserManagement\Domain\Model\Permissions\Traits\UpdatePermissionTrait;

abstract class AbstractManagementPermissions
{
    use ListPermissionTrait;
    use CreatePermissionTrait;
    use UpdatePermissionTrait;
    use DeletePermissionTrait;
}
