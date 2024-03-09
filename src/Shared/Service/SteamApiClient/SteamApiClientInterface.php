<?php

declare(strict_types=1);

namespace App\Shared\Service\SteamApiClient;

use App\Shared\Service\SteamApiClient\Dto\AppInfoDto;
use App\Shared\Service\SteamApiClient\Dto\WorkshopItemInfoDto;

interface SteamApiClientInterface
{
    public function getWorkshopItemInfo(int $itemId): WorkshopItemInfoDto;

    public function getAppInfo(int $appId): AppInfoDto;
}
