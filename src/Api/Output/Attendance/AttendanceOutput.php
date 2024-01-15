<?php

declare(strict_types=1);

namespace App\Api\Output\Attendance;

class AttendanceOutput
{
    private ?string $id = null;
    private ?\DateTimeInterface $createdAt = null;
    private ?string $missionId = null;
    private ?int $playerId = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

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
