<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\Mod\ModManagementPermissions;
use App\Entity\Permissions\ModGroup\ModGroupManagementPermissions;
use App\Entity\Permissions\ModList\ModListManagementPermissions;
use App\Entity\Permissions\ModTag\ModTagManagementPermissions;
use App\Entity\Permissions\User\UserManagementPermissions;
use App\Entity\Permissions\UserGroup\UserGroupManagementPermissions;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractPermissions extends AbstractEntity implements PermissionsInterface
{
    /** @var UserManagementPermissions */
    protected $userManagementPermissions;

    /** @var UserGroupManagementPermissions */
    protected $userGroupManagementPermissions;

    /** @var ModManagementPermissions */
    protected $modManagementPermissions;

    /** @var ModTagManagementPermissions */
    protected $modTagManagementPermissions;

    /** @var ModGroupManagementPermissions */
    protected $modGroupManagementPermissions;

    /** @var ModListManagementPermissions */
    protected $modListManagementPermissions;

    public function __construct(UuidInterface $id)
    {
        parent::__construct($id);

        $this->userManagementPermissions = new UserManagementPermissions();
        $this->userGroupManagementPermissions = new UserGroupManagementPermissions();
        $this->modManagementPermissions = new ModManagementPermissions();
        $this->modTagManagementPermissions = new ModTagManagementPermissions();
        $this->modGroupManagementPermissions = new ModGroupManagementPermissions();
        $this->modListManagementPermissions = new ModListManagementPermissions();
    }

    public function getUserManagementPermissions(): UserManagementPermissions
    {
        return $this->userManagementPermissions;
    }

    public function getUserGroupManagementPermissions(): UserGroupManagementPermissions
    {
        return $this->userGroupManagementPermissions;
    }

    public function getModManagementPermissions(): ModManagementPermissions
    {
        return $this->modManagementPermissions;
    }

    public function getModTagManagementPermissions(): ModTagManagementPermissions
    {
        return $this->modTagManagementPermissions;
    }

    public function getModGroupManagementPermissions(): ModGroupManagementPermissions
    {
        return $this->modGroupManagementPermissions;
    }

    public function getModListManagementPermissions(): ModListManagementPermissions
    {
        return $this->modListManagementPermissions;
    }

    public function grantAll(): void
    {
        $this->getUserManagementPermissions()->setList(true);
        $this->getUserManagementPermissions()->setManagePermissions(true);
        $this->getUserManagementPermissions()->setDelete(true);

        $this->getUserGroupManagementPermissions()->setList(true);
        $this->getUserGroupManagementPermissions()->setCreate(true);
        $this->getUserGroupManagementPermissions()->setUpdate(true);
        $this->getUserGroupManagementPermissions()->setDelete(true);

        $this->getModManagementPermissions()->setList(true);
        $this->getModManagementPermissions()->setCreate(true);
        $this->getModManagementPermissions()->setUpdate(true);
        $this->getModManagementPermissions()->setDelete(true);
        $this->getModManagementPermissions()->setChangeStatus(true);

        $this->getModGroupManagementPermissions()->setList(true);
        $this->getModGroupManagementPermissions()->setCreate(true);
        $this->getModGroupManagementPermissions()->setUpdate(true);
        $this->getModGroupManagementPermissions()->setDelete(true);

        $this->getModListManagementPermissions()->setList(true);
        $this->getModListManagementPermissions()->setCreate(true);
        $this->getModListManagementPermissions()->setUpdate(true);
        $this->getModListManagementPermissions()->setDelete(true);
        $this->getModListManagementPermissions()->setCopy(true);
        $this->getModListManagementPermissions()->setApprove(true);
    }
}
