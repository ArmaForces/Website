<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\ModLists\ModListsPermissions;
use App\Entity\Permissions\Mods\ModsPermissions;
use App\Entity\Permissions\Users\UsersPermissions;

class Permissions extends AbstractEntity
{
    /** @var UsersPermissions */
    protected $usersPermissions;

    /** @var ModsPermissions */
    protected $modsPermissions;

    /** @var ModListsPermissions */
    protected $modListsPermissions;

    public function __construct()
    {
        parent::__construct();

        $this->usersPermissions = new UsersPermissions();
        $this->modsPermissions = new ModsPermissions();
        $this->modListsPermissions = new ModListsPermissions();
    }

    public function getUsersPermissions(): UsersPermissions
    {
        return $this->usersPermissions;
    }

    public function getModsPermissions(): ModsPermissions
    {
        return $this->modsPermissions;
    }

    public function getModListsPermissions(): ModListsPermissions
    {
        return $this->modListsPermissions;
    }
}
