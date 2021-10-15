<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;
use Ramsey\Uuid\UuidInterface;

class SteamWorkshopMod extends AbstractMod implements SteamWorkshopModInterface
{
    public function __construct(
        UuidInterface $id,
        string $name,
        ModTypeEnum $type,
        private int $itemId
    ) {
        parent::__construct($id, $name, $type);
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
