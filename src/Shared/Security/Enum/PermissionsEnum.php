<?php

declare(strict_types=1);

namespace App\Shared\Security\Enum;

enum PermissionsEnum: string
{
    case USER_LIST = 'user_list';
    case USER_UPDATE = 'user_update';
    case USER_DELETE = 'user_delete';

    case USER_GROUP_LIST = 'user_group_list';
    case USER_GROUP_CREATE = 'user_group_create';
    case USER_GROUP_UPDATE = 'user_group_update';
    case USER_GROUP_DELETE = 'user_group_delete';

    case MOD_LIST = 'mod_list';
    case MOD_CREATE = 'mod_create';
    case MOD_UPDATE = 'mod_update';
    case MOD_DELETE = 'mod_delete';

    case MOD_GROUP_LIST = 'mod_group_list';
    case MOD_GROUP_CREATE = 'mod_group_create';
    case MOD_GROUP_UPDATE = 'mod_group_update';
    case MOD_GROUP_DELETE = 'mod_group_delete';

    case DLC_LIST = 'dlc_list';
    case DLC_CREATE = 'dlc_create';
    case DLC_UPDATE = 'dlc_update';
    case DLC_DELETE = 'dlc_delete';

    case MOD_LIST_LIST = 'mod_list_list';
    case MOD_LIST_DOWNLOAD = 'mod_list_download';
    case MOD_LIST_CREATE = 'mod_list_create';
    case MOD_LIST_UPDATE = 'mod_list_update';
    case MOD_LIST_COPY = 'mod_list_copy';
    case MOD_LIST_DELETE = 'mod_list_delete';
    case MOD_LIST_APPROVE = 'mod_list_approve';
}
