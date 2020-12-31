<?php

declare(strict_types=1);

namespace App\Pmc\Service\Dynulo;

use App\Pmc\Service\Dynulo\Dto\ItemDto;
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

    public function createItem(ItemDto $item): int
    {
        $response = $this->httpClient->request('POST', 'v2/items', [
            'json' => $item,
        ]);

        return $response->getStatusCode();
    }

    public function deleteItem(string $className): int
    {
        $response = $this->httpClient->request('DELETE', "v2/items/{$className}");

        return $response->getStatusCode();
    }
}
