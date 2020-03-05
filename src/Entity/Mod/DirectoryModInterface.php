<?php

declare(strict_types=1);

namespace App\Entity\Mod;

interface DirectoryModInterface extends ModInterface
{
    public function getPath(): string;

    public function setPath(string $path): void;
}
