<?php

declare(strict_types=1);

namespace App\Service\SteamWorkshop;

use App\Service\SteamWorkshop\Dto\SteamWorkshopItemInfoDto;
use App\Service\SteamWorkshop\Exception\ItemNotFoundException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SteamWorkshopClient
{
    /** @var HttpClientInterface */
    protected $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getWorkshopItemInfo(int $itemId): SteamWorkshopItemInfoDto
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
            throw new ItemNotFoundException(sprintf('No items found by item id "%s"!', $itemId));
        }

        $publishedFileDetails = $responseKey['publishedfiledetails'][0];
        $itemName = $publishedFileDetails['title'];
        $gameId = $publishedFileDetails['creator_app_id'];

        return new SteamWorkshopItemInfoDto($itemId, $itemName, $gameId);
    }
}
