<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\Permissions\Mod\ModManagementPermissions;
use App\Entity\Permissions\ModGroup\ModGroupManagementPermissions;
use App\Entity\Permissions\ModList\ModListManagementPermissions;
use App\Entity\Permissions\ModTag\ModTagManagementPermissions;
use App\Entity\Permissions\User\UserManagementPermissions;
use App\Entity\Permissions\UserGroup\UserGroupManagementPermissions;

interface PermissionsInterface
{
    public function getUserManagementPermissions(): UserManagementPermissions;

    public function getUserGroupManagementPermissions(): UserGroupManagementPermissions;

    public function getModManagementPermissions(): ModManagementPermissions;

    public function getModTagManagementPermissions(): ModTagManagementPermissions;

    public function getModGroupManagementPermissions(): ModGroupManagementPermissions;

    public function getModListManagementPermissions(): ModListManagementPermissions;

    public function grantAll(): void;
}
