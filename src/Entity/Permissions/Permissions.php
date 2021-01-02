<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;
use App\Entity\Permissions\Mod\ModPermissions;
use App\Entity\Permissions\ModGroup\ModGroupPermissions;
use App\Entity\Permissions\ModList\ModListPermissions;
use App\Entity\Permissions\User\UserPermissions;
use Ramsey\Uuid\UuidInterface;

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

    public function __construct(UuidInterface $id)
    {
        parent::__construct($id);

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

    public function grantAll(): void
    {
        $this->getUserPermissions()->setList(true);
        $this->getUserPermissions()->setManagePermissions(true);
        $this->getUserPermissions()->setDelete(true);

        $this->getModPermissions()->setList(true);
        $this->getModPermissions()->setCreate(true);
        $this->getModPermissions()->setUpdate(true);
        $this->getModPermissions()->setDelete(true);

        $this->getModGroupPermissions()->setList(true);
        $this->getModGroupPermissions()->setCreate(true);
        $this->getModGroupPermissions()->setUpdate(true);
        $this->getModGroupPermissions()->setDelete(true);

        $this->getModListPermissions()->setList(true);
        $this->getModListPermissions()->setCreate(true);
        $this->getModListPermissions()->setUpdate(true);
        $this->getModListPermissions()->setDelete(true);
        $this->getModListPermissions()->setCopy(true);
        $this->getModListPermissions()->setApprove(true);
    }
}
