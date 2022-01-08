<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions;

use App\SharedKernel\Domain\Model\AbstractBlamableEntity;
use App\UserManagement\Domain\Model\Permissions\Dlc\DlcManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\Mod\ModManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\ModGroup\ModGroupManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\ModList\ModListManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\User\UserManagementPermissions;
use App\UserManagement\Domain\Model\Permissions\UserGroup\UserGroupManagementPermissions;
use Ramsey\Uuid\UuidInterface;

abstract class AbstractPermissions extends AbstractBlamableEntity implements PermissionsInterface
{
    protected UserManagementPermissions $userManagementPermissions;
    protected UserGroupManagementPermissions $userGroupManagementPermissions;
    protected ModManagementPermissions $modManagementPermissions;
    protected ModGroupManagementPermissions $modGroupManagementPermissions;
    protected DlcManagementPermissions $dlcManagementPermissions;
    protected ModListManagementPermissions $modListManagementPermissions;

    public function __construct(UuidInterface $id)
    {
        parent::__construct($id);

        $this->userManagementPermissions = new UserManagementPermissions();
        $this->userGroupManagementPermissions = new UserGroupManagementPermissions();
        $this->modManagementPermissions = new ModManagementPermissions();
        $this->modGroupManagementPermissions = new ModGroupManagementPermissions();
        $this->dlcManagementPermissions = new DlcManagementPermissions();
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

    public function getModGroupManagementPermissions(): ModGroupManagementPermissions
    {
        return $this->modGroupManagementPermissions;
    }

    public function getDlcManagementPermissions(): DlcManagementPermissions
    {
        return $this->dlcManagementPermissions;
    }

    public function getModListManagementPermissions(): ModListManagementPermissions
    {
        return $this->modListManagementPermissions;
    }

    public function grantAll(): void
    {
        $this->getUserManagementPermissions()->setList(true);
        $this->getUserManagementPermissions()->setUpdate(true);
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

        $this->getDlcManagementPermissions()->setList(true);
        $this->getDlcManagementPermissions()->setCreate(true);
        $this->getDlcManagementPermissions()->setUpdate(true);
        $this->getDlcManagementPermissions()->setDelete(true);

        $this->getModListManagementPermissions()->setList(true);
        $this->getModListManagementPermissions()->setCreate(true);
        $this->getModListManagementPermissions()->setUpdate(true);
        $this->getModListManagementPermissions()->setDelete(true);
        $this->getModListManagementPermissions()->setCopy(true);
        $this->getModListManagementPermissions()->setApprove(true);
    }
}
