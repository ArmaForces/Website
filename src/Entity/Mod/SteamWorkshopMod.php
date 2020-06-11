<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;

class SteamWorkshopMod extends AbstractMod implements SteamWorkshopModInterface
{
    /** @var int */
    protected $itemId;

    public function __construct(string $name, ModTypeEnum $type, int $itemId)
    {
        parent::__construct($name, $type);

        $this->itemId = $itemId;
    }

    public function getItemId(): int
    {
        return $this->itemId;
    }

    public function setItemId(int $itemId): void
    {
        $this->itemId = $itemId;
    }
}
