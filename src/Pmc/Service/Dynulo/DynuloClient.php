<?php

declare(strict_types=1);

namespace App\Pmc\Service\Dynulo;

use App\Pmc\Service\Dynulo\Dto\ItemDto;
use App\Pmc\Service\Dynulo\Dto\PlayerDto;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DynuloClient
{
    public const AUTH_HEADER = 'x-dynulo-guild-token';

    /** @var HttpClientInterface */
    protected $httpClient;

    public function __construct(HttpClientInterface $httpClient, string $dynuloUrl, string $dynuloToken)
    {
        $this->httpClient = ScopingHttpClient::forBaseUri($httpClient, $dynuloUrl, [
            'headers' => [
                self::AUTH_HEADER => $dynuloToken,
            ],
        ]);
    }

    /**
     * @return ItemDto[]
     */
    public function getItems(): array
    {
        $response = $this->httpClient->request('GET', 'v2/items');

        return array_map(static function ($x) {return ItemDto::fromArray($x); }, $response->toArray());
    }

    public function getItem(string $className): ItemDto
    {
        $response = $this->httpClient->request('GET', "v2/items/{$className}");

        return ItemDto::fromArray($response->toArray());
    }

    public function createItem(ItemDto $item): void
    {
        $response = $this->httpClient->request('POST', 'v2/items', [
            'json' => $item,
        ]);

        // ensure exceptions on non 200 responses
        $response->getContent();
    }

    public function deleteItem(string $className): void
    {
        $response = $this->httpClient->request('DELETE', "v2/items/{$className}");

        // ensure exceptions on non 200 responses
        $response->getContent();
    }

    /**
     * @return PlayerDto[]
     */
    public function getPlayers(): array
    {
        $response = $this->httpClient->request('GET', 'v2/players');

        return array_map(static function ($x) {return PlayerDto::fromArray($x); }, $response->toArray());
    }

    public function getPlayer(int $steamId): PlayerDto
    {
        $response = $this->httpClient->request('GET', "v2/players/{$steamId}");

        return PlayerDto::fromArray($response->toArray());
    }
}
