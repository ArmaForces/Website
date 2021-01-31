<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\Permissions\Mod\ModManagementPermissions;
use App\Entity\Permissions\ModGroup\ModGroupManagementPermissions;
use App\Entity\Permissions\ModList\ModListManagementPermissions;
use App\Entity\Permissions\User\UserManagementPermissions;

interface PermissionsInterface
{
    public function getUserManagementPermissions(): UserManagementPermissions;

    public function getModManagementPermissions(): ModManagementPermissions;

    public function getModGroupManagementPermissions(): ModGroupManagementPermissions;

    public function getModListManagementPermissions(): ModListManagementPermissions;

    public function grantAll(): void;
}
