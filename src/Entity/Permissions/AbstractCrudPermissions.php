<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\Permissions\Traits\CreatePermissionTrait;
use App\Entity\Permissions\Traits\DeletePermissionTrait;
use App\Entity\Permissions\Traits\ListPermissionTrait;
use App\Entity\Permissions\Traits\UpdatePermissionTrait;

abstract class AbstractCrudPermissions
{
    use ListPermissionTrait;
    use CreatePermissionTrait;
    use UpdatePermissionTrait;
    use DeletePermissionTrait;
}
