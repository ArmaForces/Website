<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\DescribedEntityInterface;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\ModList\ModListInterface;

interface ModInterface extends DescribedEntityInterface
{
    public function getType(): ModTypeEnum;

    public function setType(ModTypeEnum $type): void;

    public function addModList(ModListInterface $modsList): void;

    public function removeModList(ModListInterface $modsList): void;

    /**
     * @return ModListInterface[]
     */
    public function getModLists(): array;
}
