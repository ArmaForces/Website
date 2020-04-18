<?php

declare(strict_types=1);

namespace App\Entity\Permissions\ModList;

use App\Entity\Permissions\AbstractCrudPermissions;

class ModListPermissions extends AbstractCrudPermissions
{
    /** @var bool */
    protected $copy = false;

    public function canCopy(): bool
    {
        return $this->copy;
    }

    public function setCopy(bool $copy): void
    {
        $this->copy = $copy;
    }
}
