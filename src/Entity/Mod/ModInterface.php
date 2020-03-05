<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\DescribedEntityInterface;
use App\Entity\Mod\Enum\ModTypeEnum;

interface ModInterface extends DescribedEntityInterface
{
    public function getType(): ModTypeEnum;

    public function setType(ModTypeEnum $type): void;
}
