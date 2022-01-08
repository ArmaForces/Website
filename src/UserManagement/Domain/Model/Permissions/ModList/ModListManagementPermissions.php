<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions\ModList;

use App\UserManagement\Domain\Model\Permissions\AbstractManagementPermissions;

class ModListManagementPermissions extends AbstractManagementPermissions
{
    private bool $copy = false;
    private bool $approve = false;

    public function canCopy(): bool
    {
        return $this->copy;
    }

    public function setCopy(bool $copy): void
    {
        $this->copy = $copy;
    }

    public function canApprove(): bool
    {
        return $this->approve;
    }

    public function setApprove(bool $approve): void
    {
        $this->approve = $approve;
    }
}
