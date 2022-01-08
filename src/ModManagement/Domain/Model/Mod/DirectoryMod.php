<?php

declare(strict_types=1);

namespace App\ModManagement\Domain\Model\Mod;

use App\ModManagement\Domain\Model\Mod\Enum\ModTypeEnum;
use Ramsey\Uuid\UuidInterface;

class DirectoryMod extends AbstractMod implements DirectoryModInterface
{
    public function __construct(
        UuidInterface $id,
        string $name,
        ModTypeEnum $type,
        private string $directory
    ) {
        parent::__construct($id, $name, $type);
    }

    public function getDirectory(): string
    {
        return $this->directory;
    }

    public function setDirectory(string $directory): void
    {
        $this->directory = $directory;
    }
}
