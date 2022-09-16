<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport\Dto;

class ModlistInfo
{
    public function __construct(
        private string $modlistName,
        private array $mods,
    ) {
    }

    public function getModlistName(): string
    {
        return $this->modlistName;
    }

    public function getMods(): array
    {
        return $this->mods;
    }
}
