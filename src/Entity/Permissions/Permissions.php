<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\Mod\ModPermissions;
use App\Entity\Permissions\ModGroup\ModGroupPermissions;
use App\Entity\Permissions\ModList\ModListPermissions;
use App\Entity\Permissions\User\UserPermissions;

class Permissions extends AbstractEntity
{
    /** @var UserPermissions */
    protected $userPermissions;

    /** @var ModPermissions */
    protected $modPermissions;

    /** @var ModGroupPermissions */
    protected $modGroupPermissions;

    /** @var ModListPermissions */
    protected $modListPermissions;

    public function __construct()
    {
        parent::__construct();

        $this->userPermissions = new UserPermissions();
        $this->modPermissions = new ModPermissions();
        $this->modGroupPermissions = new ModGroupPermissions();
        $this->modListPermissions = new ModListPermissions();
    }

    public function getUserPermissions(): UserPermissions
    {
        return $this->userPermissions;
    }

    public function getModPermissions(): ModPermissions
    {
        return $this->modPermissions;
    }

    public function getModGroupPermissions(): ModGroupPermissions
    {
        return $this->modGroupPermissions;
    }

    public function getModListPermissions(): ModListPermissions
    {
        return $this->modListPermissions;
    }
}
