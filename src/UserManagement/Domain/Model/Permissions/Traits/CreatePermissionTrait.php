<?php

declare(strict_types=1);

namespace App\UserManagement\Domain\Model\Permissions\Traits;

trait CreatePermissionTrait
{
    protected bool $create = false;

    public function canCreate(): bool
    {
        return $this->create;
    }

    public function setCreate(bool $create): void
    {
        $this->create = $create;
    }
}
