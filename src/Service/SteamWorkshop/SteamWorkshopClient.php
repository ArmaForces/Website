<?php

declare(strict_types=1);

namespace App\Service\SteamWorkshop;

use App\Service\SteamWorkshop\Dto\SteamWorkshopItemInfoDto;
use App\Service\SteamWorkshop\Exception\ItemNotFoundException;
use App\Service\SteamWorkshop\Exception\SteamWorkshopClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class SteamWorkshopClient
{
    /** @var HttpClientInterface */
    protected $httpClient;

    public function __construct(HttpClientInterface $httpClient)
    {
        $this->httpClient = $httpClient;
    }

    public function getWorkshopItemInfo(string $itemUrl): SteamWorkshopItemInfoDto
    {
        $itemId = $this->getItemIdFromUrl($itemUrl);

        $url = 'https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/';
        $response = $this->httpClient->request('POST', $url, [
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

    protected function getItemIdFromUrl(string $url): int
    {
        $matches = [];
        $result = preg_match('/(?<=\?id=)\d{10}/', $url, $matches);
        if (1 === $result) {
            return (int) $matches[0];
        }

        throw new SteamWorkshopClientException(sprintf('Unable to retrieve item id for item url "%s"!', $url));
    }
}
