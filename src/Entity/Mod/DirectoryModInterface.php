<?php

declare(strict_types=1);

namespace App\Entity\Mod;

interface DirectoryModInterface extends ModInterface
{
    public function getDirectory(): string;

    public function setDirectory(string $directory): void;
}
