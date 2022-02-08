<?php

declare(strict_types=1);

namespace App\Tests\Unit\SharedKernel\Infrastructure\Service\Steam\Helper;

use App\SharedKernel\Infrastructure\Service\Steam\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\SharedKernel\Infrastructure\Service\Steam\Helper\SteamHelper;
use PHPUnit\Framework\TestCase;

/**
 * @internal
 * @covers \App\SharedKernel\Infrastructure\Service\Steam\Helper\SteamHelper
 */
final class SteamHelperTest extends TestCase
{
    protected const ITEM_ID = 1934142795;

    /**
     * @test
     * @dataProvider validItemUrls
     */
    public function isValidItemUrl_validItemUrl_returnsTrue(string $itemUrl): void
    {
        $result = SteamHelper::isValidItemUrl($itemUrl);
        static::assertTrue($result);
    }

    /**
     * @test
     * @dataProvider invalidItemUrls
     */
    public function isValidItemUrl_invalidItemUrl_returnsFalse(string $itemUrl): void
    {
        $result = SteamHelper::isValidItemUrl($itemUrl);
        static::assertFalse($result);
    }

    /**
     * @test
     */
    public function itemIdToItemUrl_validItemId_returnsUrl(): void
    {
        $itemUrl = SteamHelper::itemIdToItemUrl($this::ITEM_ID);
        static::assertSame('https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795', $itemUrl);
    }

    /**
     * @test
     * @dataProvider validItemUrls
     */
    public function itemUrlToItemId_validItemUrl_returnsItemId(string $itemUrl): void
    {
        $itemId = SteamHelper::itemUrlToItemId($itemUrl);
        static::assertSame($this::ITEM_ID, $itemId);
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
        $this->expectException(InvalidWorkshopItemUrlFormatException::class);
        $this->expectExceptionMessage("Invalid item URL format for: \"{$itemUrl}\"");

        SteamHelper::itemUrlToItemId($itemUrl);
    }

    public function invalidItemUrls(): array
    {
        return [
            ['invalid url'],
        ];
    }
}
