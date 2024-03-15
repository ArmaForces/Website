<?php

declare(strict_types=1);

namespace App\Users\Entity\Permissions;

use App\Shared\Entity\Common\AbstractBlamableEntity;

abstract class AbstractPermissions extends AbstractBlamableEntity
{
    // User
    public bool $userList = false;
    public bool $userUpdate = false;
    public bool $userDelete = false;

    // User Group
    public bool $userGroupList = false;
    public bool $userGroupCreate = false;
    public bool $userGroupUpdate = false;
    public bool $userGroupDelete = false;

    // Mod
    public bool $modList = false;
    public bool $modCreate = false;
    public bool $modUpdate = false;
    public bool $modDelete = false;
    public bool $modChangeStatus = false;

    // Mod Group
    public bool $modGroupList = false;
    public bool $modGroupCreate = false;
    public bool $modGroupUpdate = false;
    public bool $modGroupDelete = false;

    // Dlc
    public bool $dlcList = false;
    public bool $dlcCreate = false;
    public bool $dlcUpdate = false;
    public bool $dlcDelete = false;

    // Mod List
    public bool $modListList = false;
    public bool $modListCreate = false;
    public bool $modListUpdate = false;
    public bool $modListDelete = false;
    public bool $modListCopy = false;
    public bool $modListApprove = false;

    public function grantAll(): void
    {
        // User
        $this->userList = true;
        $this->userUpdate = true;
        $this->userDelete = true;

        // User Group
        $this->userGroupList = true;
        $this->userGroupCreate = true;
        $this->userGroupUpdate = true;
        $this->userGroupDelete = true;

        // Mod
        $this->modList = true;
        $this->modCreate = true;
        $this->modUpdate = true;
        $this->modDelete = true;
        $this->modChangeStatus = true;

        // Mod Group
        $this->modGroupList = true;
        $this->modGroupCreate = true;
        $this->modGroupUpdate = true;
        $this->modGroupDelete = true;

        // Dlc
        $this->dlcList = true;
        $this->dlcCreate = true;
        $this->dlcUpdate = true;
        $this->dlcDelete = true;

        // Mod List
        $this->modListList = true;
        $this->modListCreate = true;
        $this->modListUpdate = true;
        $this->modListDelete = true;
        $this->modListCopy = true;
        $this->modListApprove = true;
    }
}
