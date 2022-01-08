<?php

declare(strict_types=1);

namespace App\ModManagement\Domain\Model\ModGroup;

use App\ModManagement\Domain\Model\Mod\ModInterface;
use App\SharedKernel\Domain\Model\BlamableEntityInterface;
use App\SharedKernel\Domain\Model\Traits\DescribedInterface;
use App\SharedKernel\Domain\Model\Traits\NamedInterface;

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
