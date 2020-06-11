<?php

declare(strict_types=1);

namespace App\Tests\Service\SteamWorkshop\Helper;

use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @coversNothing
 */
final class SteamWorkshopHelperTest extends TestCase
{
    protected const ITEM_ID = 1934142795;
    protected const ITEM_URL = 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795';

    public function testItemIdToItemUrl(): void
    {
        $itemUrl = SteamWorkshopHelper::itemIdToItemUrl(self::ITEM_ID);
        static::assertSame(self::ITEM_URL, $itemUrl);
    }

    public function testItemUrlToItemId(): void
    {
        $itemUrl = SteamWorkshopHelper::itemUrlToItemId(self::ITEM_URL);
        static::assertSame(self::ITEM_ID, $itemUrl);
    }
}
