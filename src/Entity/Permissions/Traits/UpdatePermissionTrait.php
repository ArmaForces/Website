<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Traits;

trait UpdatePermissionTrait
{
    /** @var bool */
    protected $update = false;

    public function canUpdate(): bool
    {
        return $this->update;
    }

    public function setUpdate(bool $update): void
    {
        $this->update = $update;
    }
}
