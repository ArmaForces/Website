<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Traits;

trait DeletePermissionTrait
{
    /** @var bool */
    protected $delete = false;

    public function canDelete(): bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): void
    {
        $this->delete = $delete;
    }
}
