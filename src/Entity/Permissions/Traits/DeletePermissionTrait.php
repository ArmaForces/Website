<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Traits;

trait DeletePermissionTrait
{
    protected bool $delete = false;

    public function canDelete(): bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): void
    {
        $this->delete = $delete;
    }
}
