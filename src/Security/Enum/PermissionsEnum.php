<?php

declare(strict_types=1);

namespace App\Security\Enum;

class PermissionsEnum
{
    public const USERS_MANAGE_PERMISSIONS = 'users_manage_permissions';
    public const USERS_LIST = 'users_list';
    public const USERS_DELETE = 'users_delete';

    public const MODS_LIST = 'mods_list';
    public const MODS_CREATE = 'mods_create';
    public const MODS_UPDATE = 'mods_update';
    public const MODS_DELETE = 'mods_delete';
}
