<?php

declare(strict_types=1);

namespace App\Entity\Permissions\ModList;

use App\Entity\Permissions\AbstractCrudPermissions;

class ModListPermissions extends AbstractCrudPermissions
{
    /** @var bool */
    protected $copy = false;

    /** @var bool */
    protected $approve = false;

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
