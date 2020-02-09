<?php

declare(strict_types=1);

namespace App\Service\Mission;

use App\Enum\Mission\MissionStateEnum;
use App\Service\Mission\Dto\MissionDto;
use Symfony\Component\HttpClient\CachingHttpClient;
use Symfony\Component\HttpClient\ScopingHttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class MissionClient
{
    /** @var HttpClientInterface */
    protected $client;

    public function __construct(HttpClientInterface $client, MissionStore $store, string $missionApiUrl)
    {
        $cachingClient = new CachingHttpClient($client, $store, [
            // allow 12h of stale response
            'stale_if_error' => 43200,
        ]);

        $this->client = ScopingHttpClient::forBaseUri($cachingClient, $missionApiUrl);
    }

    /**
     * @return \Generator|MissionDto[]
     */
    public function getMissions(bool $includeArchive = true, int $ttl = 600): \Generator
    {
        $response = $this->client->request('GET', '/api/missions', [
            'query' => [
                'includeArchive' => $includeArchive ? 'true' : 'false',
                'ttl' => $ttl,
            ],
        ]);

        foreach ($response->toArray() as $mission) {
            yield MissionDto::fromArray($mission);
        }
    }

    /**
     * Get nearest open or archived mission.
     */
    public function getNearestMission(): ?MissionDto
    {
        $allMissions = $this->getMissions(true);
        $nearestMission = null;

        foreach ($allMissions as $mission) {
            if ($nearestMission && MissionStateEnum::ARCHIVED === $mission->getState()) {
                break;
            }
            if (MissionStateEnum::ARCHIVED === $mission->getState()) {
                $nearestMission = $mission;

                break;
            }

            $nearestMission = $mission;
        }

        return $nearestMission;
    }

    /**
     * @return MissionDto[]
     */
    public function getArchivedMissions(): array
    {
        /** @var MissionDto[] $allMissions */
        $allMissions = iterator_to_array($this->getMissions(true));

        $firstArchivedIndex = -1;
        foreach ($allMissions as $idx => $mission) {
            if (MissionStateEnum::ARCHIVED === $mission->getState()) {
                $firstArchivedIndex = $idx;

                break;
            }
        }

        return -1 === $firstArchivedIndex ? [] : \array_slice($allMissions, $firstArchivedIndex);
    }
}
