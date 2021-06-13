<?php

declare(strict_types=1);

namespace App\Api\Dto;

class ModListDetailsOutput extends ModListOutput
{
    protected array $mods = [];
    protected array $dlcs = [];

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

    /**
     * @return DlcOutput[]
     */
    public function getDlcs(): array
    {
        return $this->dlcs;
    }

    /**
     * @param DlcOutput[] $dlcs
     */
    public function setDlcs(array $dlcs): void
    {
        $this->dlcs = $dlcs;
    }
}
