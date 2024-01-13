<?php

declare(strict_types=1);

namespace App\Api\Input\Attendance;

use App\Validator\Attendance\UniqueAttendance;
use App\Validator\SteamProfileId;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueAttendance]
class AttendanceInput
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    private ?string $missionId = null;

    #[Assert\NotBlank]
    #[SteamProfileId]
    private ?int $playerId = null;

    public function getMissionId(): ?string
    {
        return $this->missionId;
    }

    public function setMissionId(?string $missionId): void
    {
        $this->missionId = $missionId;
    }

    public function getPlayerId(): ?int
    {
        return $this->playerId;
    }

    public function setPlayerId(?int $playerId): void
    {
        $this->playerId = $playerId;
    }
}
