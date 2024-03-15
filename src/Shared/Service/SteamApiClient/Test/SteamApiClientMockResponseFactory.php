<?php

declare(strict_types=1);

namespace App\Shared\Service\SteamApiClient\Test;

use Symfony\Component\HttpClient\Response\MockResponse;
use Symfony\Contracts\HttpClient\ResponseInterface;

class SteamApiClientMockResponseFactory
{
    public function __invoke(string $method, string $url, array $options = []): ResponseInterface
    {
        return match (true) {
            'POST' === $method && 'https://api.steampowered.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/' === $url => $this->getGetPublishedFileDetailsResponse($method, $url, $options),
            'GET' === $method && str_starts_with($url, 'https://store.steampowered.com/api/appdetails') => $this->getStoreResponse($method, $url, $options),
            default => throw new \InvalidArgumentException('Unsupported request provided')
        };
    }

    private function getGetPublishedFileDetailsResponse(string $method, string $url, array $options = []): MockResponse
    {
        $params = $this->parseFormDataParameters($options);
        $publishedFileIds = $params['publishedfileids[0]'];
        $path = sprintf('%s/data/GetPublishedFileDetails/%s.json', __DIR__, $publishedFileIds);
        $json = @file_get_contents($path);
        if (!$json) {
            return $this->createPublishedFileDetailsNotFoundResponse($publishedFileIds);
        }

        return $this->createJsonResponse($json);
    }

    private function createPublishedFileDetailsNotFoundResponse(string $publishedFileIds): MockResponse
    {
        return $this->createJsonResponse([
            'response' => [
                'result' => 1,
                'resultcount' => 1,
                'publishedfiledetails' => [
                    [
                        'publishedfileid' => $publishedFileIds,
                        'result' => 1,
                    ],
                ],
            ],
        ]);
    }

    private function getStoreResponse(string $method, string $url, array $options = []): MockResponse
    {
        $appId = $options['query']['appids'];
        $path = sprintf('%s/data/appdetails/%s.json', __DIR__, $appId);
        $json = @file_get_contents($path);
        if (!$json) {
            return $this->createAppDetailsNotFoundResponse($appId);
        }

        return $this->createJsonResponse($json);
    }

    private function createAppDetailsNotFoundResponse(int $appId): MockResponse
    {
        return $this->createJsonResponse([
            $appId => [
                'success' => false,
            ],
        ]);
    }

    private function parseFormDataParameters(array $options): array
    {
        $params = [];
        $formDataParams = explode('&', urldecode($options['body']));
        foreach ($formDataParams as $formDataParam) {
            [$paramName, $paramValue] = explode('=', $formDataParam);
            $params[$paramName] = $paramValue;
        }

        return $params;
    }

    private function createJsonResponse(array|string $json): MockResponse
    {
        if (\is_array($json)) {
            $json = json_encode($json, JSON_THROW_ON_ERROR);
        }

        return new MockResponse($json, [
            'response_headers' => [
                'content-type' => 'application/json',
            ],
        ]);
    }
}
