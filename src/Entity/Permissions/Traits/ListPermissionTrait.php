<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Traits;

trait ListPermissionTrait
{
    protected bool $list = false;

    public function canList(): bool
    {
        return $this->list;
    }

    public function setList(bool $list): void
    {
        $this->list = $list;
    }
}
