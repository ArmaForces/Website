<?php

declare(strict_types=1);

namespace App\Entity\Mod;

interface SteamWorkshopModInterface extends ModInterface
{
    public function getUrl(): string;

    public function setUrl(string $url): void;
}
