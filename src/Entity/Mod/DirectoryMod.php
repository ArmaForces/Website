<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModStatusEnum;
use Ramsey\Uuid\UuidInterface;

class DirectoryMod extends AbstractMod
{
    private string $directory;

    public function __construct(
        UuidInterface $id,
        string $name,
        ?string $description,
        ?ModStatusEnum $status,
        string $directory
    ) {
        parent::__construct($id, $name, $description, $status);

        $this->directory = $directory;
    }

    public function update(
        string $name,
        ?string $description,
        ?ModStatusEnum $status,
        string $directory
    ): void {
        $this->name = $name;
        $this->description = $description;
        $this->status = $status;
        $this->directory = $directory;
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }
}
