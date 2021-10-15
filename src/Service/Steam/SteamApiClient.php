<?php

declare(strict_types=1);

namespace App\Service\Steam;

use App\Service\Steam\Dto\AppInfoDto;
use App\Service\Steam\Dto\WorkshopItemInfoDto;
use App\Service\Steam\Exception\AppNotFoundException;
use App\Service\Steam\Exception\WorkshopItemNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SteamApiClient
{
    public function __construct(
        private HttpClientInterface $httpClient
    ) {
    }

    public function getWorkshopItemInfo(int $itemId): WorkshopItemInfoDto
    {
        $url = 'https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/';
        $response = $this->httpClient->request(Request::METHOD_POST, $url, [
            'body' => [
                'itemcount' => 1,
                'publishedfileids' => [
                    $itemId,
                ],
            ],
        ]);

        $responseArray = $response->toArray();
        $responseKey = $responseArray['response'];
        $itemsCount = $responseKey['resultcount'];
        if (1 !== $itemsCount) {
            throw new WorkshopItemNotFoundException(sprintf('No items found by item id "%s"!', $itemId));
        }

        $publishedFileDetails = $responseKey['publishedfiledetails'][0] ?? null;
        $name = $publishedFileDetails['title'] ?? null;
        $gameId = $publishedFileDetails['creator_app_id'] ?? null;

        return new WorkshopItemInfoDto($itemId, $name, $gameId);
    }

    public function getAppInfo(int $appId): AppInfoDto
    {
        $url = 'https://store.steampowered.com/api/appdetails';
        $response = $this->httpClient->request(Request::METHOD_GET, $url, [
            'query' => [
                'appids' => $appId,
            ],
        ]);

        $responseArray = $response->toArray();
        $responseKey = $responseArray[$appId];
        $success = $responseKey['success'];
        if (!$success) {
            throw new AppNotFoundException(sprintf('No apps found by app id "%s"!', $appId));
        }

        $data = $responseKey['data'];
        $name = $data['name'];
        $type = $data['type'] ?? null;
        $gameId = ($data['fullgame']['appid'] ?? null) ?? $appId;

        return new AppInfoDto($appId, $name, $type, (int) $gameId);
    }
}
