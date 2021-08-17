<?php

declare(strict_types=1);

namespace App\Entity\ModGroup;

use App\Entity\BlamableEntityInterface;
use App\Entity\Mod\ModInterface;
use App\Entity\Traits\DescribedInterface;
use App\Entity\Traits\NamedInterface;

interface ModGroupInterface extends BlamableEntityInterface, NamedInterface, DescribedInterface
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
