<?php

declare(strict_types=1);

namespace App\Entity\Permissions;

use App\Entity\AbstractEntity;

class Permissions extends AbstractEntity
{
    /** @var bool */
    protected $managePermissions = false;

    public function canManagePermissions(): bool
    {
        return $this->managePermissions;
    }

    public function setManagePermissions(bool $managePermissions): void
    {
        $this->managePermissions = $managePermissions;
    }
}
