<?php

declare(strict_types=1);

namespace App\Http\Mission;

use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Component\HttpKernel\HttpCache\StoreInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MissionClient
{
    /** @var HttpClientInterface */
    protected $client;

    /** @var string */
    protected $baseUrl;

    public function __construct(HttpClientInterface $client, MissionStore $store, string $missionApiUrl)
    {
        $cachingClient = new CachingHttpClient($client, $store);

        $this->client = ScopingHttpClient::forBaseUri($cachingClient, $missionApiUrl) ;
    }

    public function getMissions(bool $includeArchive = true, int $ttl = 600): array
    {
        $response = $this->client->request('GET', '/api/missions', [
            'query' => [
                'includeArchive' => $includeArchive ? 'true' : 'false',
                'ttl' => $ttl,
            ],
        ]);

        return $response->toArray();
    }
}
