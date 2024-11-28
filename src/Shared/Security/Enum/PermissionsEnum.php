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

    case STANDARD_MOD_LIST_CREATE = 'standard_mod_list_create';
    case STANDARD_MOD_LIST_UPDATE = 'standard_mod_list_update';
    case STANDARD_MOD_LIST_COPY = 'standard_mod_list_copy';
    case STANDARD_MOD_LIST_DELETE = 'standard_mod_list_delete';
    case STANDARD_MOD_LIST_APPROVE = 'standard_mod_list_approve';

    case EXTERNAL_MOD_LIST_CREATE = 'external_mod_list_create';
    case EXTERNAL_MOD_LIST_UPDATE = 'external_mod_list_update';
    case EXTERNAL_MOD_LIST_DELETE = 'external_mod_list_delete';
}
