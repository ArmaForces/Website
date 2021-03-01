<?php

declare(strict_types=1);

namespace App\Entity\Permissions\ModList;

use App\Entity\Permissions\AbstractManagementPermissions;

class ModListManagementPermissions extends AbstractManagementPermissions
{
    protected bool $copy = false;
    protected bool $approve = false;

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
