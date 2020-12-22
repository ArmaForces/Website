<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;
use Ramsey\Uuid\UuidInterface;

class DirectoryMod extends AbstractMod implements DirectoryModInterface
{
    /** @var string */
    protected $directory;

    public function __construct(UuidInterface $id, string $name, ModTypeEnum $type, string $directory)
    {
        parent::__construct($id, $name, $type);

        $this->directory = $directory;
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
