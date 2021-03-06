<?php

declare(strict_types=1);

namespace App\Entity\Mod;

use App\Entity\Mod\Enum\ModTypeEnum;
use Ramsey\Uuid\UuidInterface;

class SteamWorkshopMod extends AbstractMod implements SteamWorkshopModInterface
{
    protected string $itemId;

    public function __construct(UuidInterface $id, string $name, ModTypeEnum $type, int $itemId)
    {
        parent::__construct($id, $name, $type);

        $this->itemId = (string) $itemId;
    }

    public function getItemId(): int
    {
        return (int) $this->itemId;
    }

    public function setItemId(int $itemId): void
    {
        $this->itemId = (string) $itemId;
    }
}
