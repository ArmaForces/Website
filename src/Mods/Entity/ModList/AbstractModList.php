<?php

declare(strict_types=1);

namespace App\Mods\Entity\ModList;

use App\Shared\Entity\Common\AbstractBlamableEntity;

abstract class AbstractModList extends AbstractBlamableEntity
{
    protected string $name;
    protected ?string $description;
    protected bool $active;

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function isActive(): bool
    {
        return $this->active;
    }
}
