<?php

declare(strict_types=1);

namespace App\Service\Steam;

use App\Service\Steam\Dto\AppInfoDto;
use App\Service\Steam\Dto\WorkshopItemInfoDto;

interface SteamApiClientInterface
{
    public function getWorkshopItemInfo(int $itemId): WorkshopItemInfoDto;

    public function getAppInfo(int $appId): AppInfoDto;
}
