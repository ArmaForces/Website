<?php

declare(strict_types=1);

namespace App\Api\Output\ModList;

use App\Api\Output\Dlc\DlcOutput;
use App\Api\Output\Mod\ModOutput;

class ModListDetailsOutput extends ModListOutput
{
    private array $mods = [];
    private array $dlcs = [];

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
