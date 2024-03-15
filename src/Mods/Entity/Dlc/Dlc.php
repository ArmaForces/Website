<?php

declare(strict_types=1);

namespace App\Mods\Entity\Dlc;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use Ramsey\Uuid\UuidInterface;

class Dlc extends AbstractBlamableEntity
{
    private string $name;
    private ?string $description;
    private int $appId;
    private string $directory;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        int $appId,
        string $directory
    ) {
        parent::__construct($id);

        $this->name = $name;
        $this->description = $description;
        $this->appId = $appId;
        $this->directory = $directory;
    }

    public function update(
        string $name,
        ?string $description,
        int $appId,
        string $directory
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->appId = $appId;
        $this->directory = $directory;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getAppId(): int
    {
        return $this->appId;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
