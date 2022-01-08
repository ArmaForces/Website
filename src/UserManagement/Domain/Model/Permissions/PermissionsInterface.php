<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions;

use App\SharedKernel\Domain\Model\BlamableEntityInterface;
use App\UserManagement\Domain\Model\Permissions\Dlc\DlcManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\Mod\ModManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\ModGroup\ModGroupManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\ModList\ModListManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\User\UserManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\UserGroup\UserGroupManagementPermissions;

interface PermissionsInterface extends BlamableEntityInterface
{
    public function getUserManagementPermissions(): UserManagementPermissions;

    public function getUserGroupManagementPermissions(): UserGroupManagementPermissions;

    public function getModManagementPermissions(): ModManagementPermissions;

    public function getModGroupManagementPermissions(): ModGroupManagementPermissions;

    public function getDlcManagementPermissions(): DlcManagementPermissions;

    public function getModListManagementPermissions(): ModListManagementPermissions;

    public function grantAll(): void;
}
