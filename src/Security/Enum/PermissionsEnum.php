<?php

declare(strict_types=1);

namespace App\Security\Enum;

class PermissionsEnum
{
    public const USER_PERMISSIONS_MANAGE = 'user_permissions_manage';
    public const USER_LIST = 'user_list';
    public const USER_DELETE = 'user_delete';

    public const MOD_LIST = 'mod_list';
    public const MOD_CREATE = 'mod_create';
    public const MOD_UPDATE = 'mod_update';
    public const MOD_DELETE = 'mod_delete';

    public const MOD_GROUP_LIST = 'mod_group_list';
    public const MOD_GROUP_CREATE = 'mod_group_create';
    public const MOD_GROUP_UPDATE = 'mod_group_update';
    public const MOD_GROUP_DELETE = 'mod_group_delete';

    public const MOD_LIST_LIST = 'mod_list_list';
    public const MOD_LIST_DOWNLOAD = 'mod_list_download';
    public const MOD_LIST_CREATE = 'mod_list_create';
    public const MOD_LIST_UPDATE = 'mod_list_update';
    public const MOD_LIST_COPY = 'mod_list_copy';
    public const MOD_LIST_DELETE = 'mod_list_delete';
    public const MOD_LIST_APPROVE = 'mod_list_approve';
}
