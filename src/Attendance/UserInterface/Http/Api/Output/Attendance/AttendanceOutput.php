<?php

declare(strict_types=1);

namespace App\Attendance\UserInterface\Http\Api\Output\Attendance;

class AttendanceOutput
{
    protected ?string $id = null;
    protected ?\DateTimeInterface $createdAt = null;
    protected ?string $missionId = null;
    protected ?int $playerId = null;

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
