<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Traits;

trait CreatePermissionTrait
{
    /** @var bool */
    protected $create = false;

    public function canCreate(): bool
    {
        return $this->create;
    }

    public function setCreate(bool $create): void
    {
        $this->create = $create;
    }
}
