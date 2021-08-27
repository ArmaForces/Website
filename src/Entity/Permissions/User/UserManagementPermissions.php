<?php

declare(strict_types=1);

namespace App\Entity\Permissions\User;

use App\Entity\Permissions\Traits\DeletePermissionTrait;
use App\Entity\Permissions\Traits\ListPermissionTrait;
use App\Entity\Permissions\Traits\UpdatePermissionTrait;

class UserManagementPermissions
{
    use ListPermissionTrait;
    use UpdatePermissionTrait;
    use DeletePermissionTrait;
}
