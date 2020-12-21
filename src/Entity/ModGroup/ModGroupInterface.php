<?php

declare(strict_types=1);

namespace App\Entity\ModGroup;

use App\Entity\DescribedEntityInterface;
use App\Entity\Mod\ModInterface;

interface ModGroupInterface extends DescribedEntityInterface
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
