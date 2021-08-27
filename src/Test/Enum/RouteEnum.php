<?php

declare(strict_types=1);

namespace App\Test\Enum;

class RouteEnum
{
    public const HOME_INDEX = '/';
    public const HOME_JOIN_US = '/join-us';
    public const HOME_MISSIONS = '/missions';

    public const SECURITY_CONNECT_DISCORD = '/security/connect/discord';
    public const SECURITY_CONNECT_DISCORD_CHECK = '/security/connect/discord/check';
    public const SECURITY_LOGOUT = '/security/logout';

    public const MOD_LIST_PUBLIC_SELECT = '/mod-list/select';
    public const MOD_LIST_PUBLIC_CUSTOMIZE = '/mod-list/%s';
    public const MOD_LIST_PUBLIC_DOWNLOAD = '/mod-list/%s/download';

    public const USER_LIST = '/user/list';
    public const USER_DELETE = '/user/%s/delete';
    public const USER_PERMISSIONS = '/user/%s/permissions';

    public const USER_GROUP_LIST = '/user-group/list';
    public const USER_GROUP_CREATE = '/user-group/create';
    public const USER_GROUP_UPDATE = '/user-group/%s/edit';
    public const USER_GROUP_DELETE = '/user-group/%s/delete';

    public const MOD_LIST = '/mod/list';
    public const MOD_CREATE = '/mod/create';
    public const MOD_UPDATE = '/mod/%s/edit';
    public const MOD_DELETE = '/mod/%s/delete';

    public const MOD_GROUP_LIST = '/mod-group/list';
    public const MOD_GROUP_CREATE = '/mod-group/create';
    public const MOD_GROUP_UPDATE = '/mod-group/%s/edit';
    public const MOD_GROUP_DELETE = '/mod-group/%s/delete';

    public const MOD_LIST_LIST = '/mod-list/list';
    public const MOD_LIST_CREATE = '/mod-list/create';
    public const MOD_LIST_UPDATE = '/mod-list/%s/edit';
    public const MOD_LIST_DELETE = '/mod-list/%s/delete';

    public const API_MOD_LIST = '/api/mod-lists';
    public const API_MOD_GET_BY_ID = '/api/mod-lists/%s';
    public const API_MOD_GET_BY_NAME = '/api/mod-lists/by-name/%s';

    public const API_ATTENDANCE_LIST = '/api/attendances';
    public const API_ATTENDANCE_GET_BY_ID = '/api/attendances/%s';
    public const API_ATTENDANCE_CREATE = '/api/attendances';
}
