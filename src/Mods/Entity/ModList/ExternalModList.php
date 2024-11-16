<?php

declare(strict_types=1);

namespace App\Mods\Entity\ModList;

use Ramsey\Uuid\UuidInterface;

class ExternalModList extends AbstractModList
{
    private string $url;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        string $url,
        bool $active,
    ) {
        parent::__construct($id);

        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
        $this->active = $active;
    }

    public function update(
        string $name,
        ?string $description,
        string $url,
        bool $active,
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->url = $url;
        $this->active = $active;
    }

    public function getUrl(): string
    {
        return $this->url;
    }
}
