<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Traits;

trait ListPermissionTrait
{
    /** @var bool */
    protected $list = false;

    public function canList(): bool
    {
        return $this->list;
    }

    public function setList(bool $list): void
    {
        $this->list = $list;
    }
}
