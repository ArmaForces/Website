<?php

declare(strict_types=1);

namespace App\Entity\Mod;

interface SteamWorkshopModInterface extends ModInterface
{
    public function getItemId(): int;

    public function setItemId(int $url): void;
}
