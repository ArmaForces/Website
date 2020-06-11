<?php

declare(strict_types=1);

namespace App\Entity\ModList;

use App\Entity\DescribedEntityInterface;
use App\Entity\Mod\ModInterface;

interface ModListInterface extends DescribedEntityInterface
{
    public function addMod(ModInterface $mod): void;

    public function removeMod(ModInterface $mod): void;

    /**
     * @return ModInterface[]
     */
    public function getMods(): array;

    /**
     * @param ModInterface[] $mods
     */
    public function setMods(array $mods): void;
}
