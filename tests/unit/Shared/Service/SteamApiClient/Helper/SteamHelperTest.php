<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\Service\SteamApiClient\Helper;

use App\Shared\Service\SteamApiClient\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\Shared\Service\SteamApiClient\Helper\SteamHelper;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;

final class SteamHelperTest extends Unit
{
    protected const ITEM_ID = 1934142795;

    #[DataProvider('validItemUrls')]
    public function testValidateValidUrl(string $itemUrl): void
    {
        $result = SteamHelper::isValidItemUrl($itemUrl);
        self::assertTrue($result);
    }

    #[DataProvider('invalidItemUrls')]
    public function testValidateInvalidUrl(string $itemUrl): void
    {
        $result = SteamHelper::isValidItemUrl($itemUrl);
        self::assertFalse($result);
    }

    public function testConvertItemIdToItemUrl(): void
    {
        $itemUrl = SteamHelper::itemIdToItemUrl($this::ITEM_ID);
        self::assertSame('https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795', $itemUrl);
    }

    #[DataProvider('validItemUrls')]
    public function testConvertValidUrlToItemId(string $itemUrl): void
    {
        $itemId = SteamHelper::itemUrlToItemId($itemUrl);
        self::assertSame($this::ITEM_ID, $itemId);
    }

    #[DataProvider('invalidItemUrls')]
    public function testConvertInvalidUrlToItemId(string $itemUrl): void
    {
        $this->expectException(InvalidWorkshopItemUrlFormatException::class);
        $this->expectExceptionMessage(sprintf('Invalid item URL format for: "%s"', $itemUrl));

        SteamHelper::itemUrlToItemId($itemUrl);
    }

    protected function validItemUrls(): iterable
    {
        return [
            ['https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795'],
            ['https://steamcommunity.com/workshop/filedetails/?id=1934142795'],
        ];
    }

    protected function invalidItemUrls(): iterable
    {
        return [
            ['url' => 'invalid url'],
        ];
    }
}
