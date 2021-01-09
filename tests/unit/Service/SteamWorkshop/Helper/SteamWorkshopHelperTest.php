<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\SteamWorkshop\Helper;

use App\Service\SteamWorkshop\Helper\Exception\InvalidItemUrlFormatException;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\Service\SteamWorkshop\Helper\SteamWorkshopHelper
 */
final class SteamWorkshopHelperTest extends TestCase
{
    protected const ITEM_ID = 1934142795;

    /**
     * @test
     * @dataProvider validItemUrls
     */
    public function isValidItemUrl_validItemUrl_returnsTrue(string $itemUrl): void
    {
        $result = SteamWorkshopHelper::isValidItemUrl($itemUrl);
        $this::assertTrue($result);
    }

    /**
     * @test
     * @dataProvider invalidItemUrls
     */
    public function isValidItemUrl_invalidItemUrl_returnsFalse(string $itemUrl): void
    {
        $result = SteamWorkshopHelper::isValidItemUrl($itemUrl);
        $this::assertFalse($result);
    }

    /**
     * @test
     */
    public function itemIdToItemUrl_validItemId_returnsUrl(): void
    {
        $itemUrl = SteamWorkshopHelper::itemIdToItemUrl($this::ITEM_ID);
        $this::assertSame('https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795', $itemUrl);
    }

    /**
     * @test
     * @dataProvider validItemUrls
     */
    public function itemUrlToItemId_validItemUrl_returnsItemId(string $itemUrl): void
    {
        $itemId = SteamWorkshopHelper::itemUrlToItemId($itemUrl);
        $this::assertSame($this::ITEM_ID, $itemId);
    }

    public function validItemUrls(): array
    {
        return [
            ['https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795'],
            ['https://steamcommunity.com/workshop/filedetails/?id=1934142795'],
        ];
    }

    /**
     * @test
     * @dataProvider invalidItemUrls
     */
    public function itemUrlToItemId_invalidItemUrl_throwsException(string $itemUrl): void
    {
        $this->expectException(InvalidItemUrlFormatException::class);
        $this->expectExceptionMessage("Invalid item URL format for: \"{$itemUrl}\"");

        SteamWorkshopHelper::itemUrlToItemId($itemUrl);
    }

    public function invalidItemUrls(): array
    {
        return [
            ['invalid url'],
        ];
    }
}
