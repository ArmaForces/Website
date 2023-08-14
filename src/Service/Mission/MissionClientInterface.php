<?php

declare(strict_types=1);

namespace App\Service\Mission;

use App\Service\Mission\Dto\MissionDto;

interface MissionClientInterface
{
    public function getMissions(bool $includeArchive = true, int $ttl = 600): \Generator;

    public function getNextUpcomingMission(): ?MissionDto;

    public function getCurrentMission(): ?MissionDto;

    public function getArchivedMissions(): array;

    public function getUpcomingMissions(): array;
}
