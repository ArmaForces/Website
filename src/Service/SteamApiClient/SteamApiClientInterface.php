<?php

declare(strict_types=1);

namespace App\Service\SteamApiClient;

use App\Service\SteamApiClient\Dto\AppInfoDto;
use App\Service\SteamApiClient\Dto\WorkshopItemInfoDto;

interface SteamApiClientInterface
{
    public function getWorkshopItemInfo(int $itemId): WorkshopItemInfoDto;

    public function getAppInfo(int $appId): AppInfoDto;
}
