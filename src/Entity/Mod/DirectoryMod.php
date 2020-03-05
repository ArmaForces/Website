<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;

class DirectoryMod extends AbstractMod
{
    /** @var string */
    protected $path;

    public function __construct(string $name, ModTypeEnum $type, string $path)
    {
        parent::__construct($name, $type);

        $this->path = $path;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function setPath(string $path): void
    {
        $this->path = $path;
    }
}
