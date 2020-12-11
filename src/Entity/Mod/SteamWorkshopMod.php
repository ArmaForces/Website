<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;

class SteamWorkshopMod extends AbstractMod implements SteamWorkshopModInterface
{
    /** @var int|string */
    protected $itemId;

    public function __construct(string $name, ModTypeEnum $type, int $itemId)
    {
        parent::__construct($name, $type);

        $this->itemId = $itemId;
    }

    public function getItemId(): int
    {
        return (int) $this->itemId;
    }

    public function setItemId(int $itemId): void
    {
        $this->itemId = $itemId;
    }
}
