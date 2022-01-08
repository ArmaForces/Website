<?php

declare(strict_types=1);

namespace App\ModManagement\Domain\Model\Mod;

interface SteamWorkshopModInterface extends ModInterface
{
    public function getItemId(): int;

    public function setItemId(int $itemId): void;
}
