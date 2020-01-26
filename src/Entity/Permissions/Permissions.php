<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\Users\UsersPermissions;

class Permissions extends AbstractEntity
{
    /** @var UsersPermissions */
    protected $usersPermissions;

    public function __construct()
    {
        parent::__construct();

        $this->usersPermissions = new UsersPermissions();
    }

    public function getUsersPermissions(): UsersPermissions
    {
        return $this->usersPermissions;
    }
}
