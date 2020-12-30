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
        $response = $this->httpClient->request('GET', '/v2/items');

        return array_map(static function ($x) {return ItemDto::fromArray($x);}, $response->toArray());
    }
}
