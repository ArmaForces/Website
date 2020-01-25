<?php

declare(strict_types=1);

namespace App\Entity\Permissions\Mods;

class ModsPermissions
{
    /** @var bool */
    protected $list = false;

    /** @var bool */
    protected $create = false;

    /** @var bool */
    protected $update = false;

    /** @var bool */
    protected $delete = false;

    public function canList(): bool
    {
        return $this->list;
    }

    public function setList(bool $list): void
    {
        $this->list = $list;
    }

    public function canCreate(): bool
    {
        return $this->create;
    }

    public function setCreate(bool $create): void
    {
        $this->create = $create;
    }

    public function canUpdate(): bool
    {
        return $this->update;
    }

    public function setUpdate(bool $update): void
    {
        $this->update = $update;
    }

    public function canDelete(): bool
    {
        return $this->delete;
    }

    public function setDelete(bool $delete): void
    {
        $this->delete = $delete;
    }
}
