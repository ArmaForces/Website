<?php

declare(strict_types=1);

namespace App\Api\Dto;

class ModListDetailsOutput extends ModListOutput
{
    /**
     * @var ModOutput[]
     */
    protected $mods = [];

    /**
     * @return ModOutput[]
     */
    public function getMods(): array
    {
        return $this->mods;
    }

    /**
     * @param ModOutput[] $mods
     */
    public function setMods(array $mods): void
    {
        $this->mods = $mods;
    }
}
