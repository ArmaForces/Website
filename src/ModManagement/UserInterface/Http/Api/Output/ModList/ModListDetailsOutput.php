<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Api\Output\ModList;

use App\ModManagement\UserInterface\Http\Api\Output\Dlc\DlcOutput;
use App\ModManagement\UserInterface\Http\Api\Output\Mod\ModOutput;

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
