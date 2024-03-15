<?php

declare(strict_types=1);

namespace App\Attendances\Entity\Attendance;

use App\Shared\Entity\Common\AbstractEntity;
use Ramsey\Uuid\UuidInterface;

class Attendance extends AbstractEntity
{
    private string $missionId;
    private int $playerId;

    public function __construct(
        UuidInterface $id,
        string $missionId,
        int $playerId
    ) {
        parent::__construct($id);

        $this->missionId = $missionId;
        $this->playerId = $playerId;
    }

    public function getMissionId(): string
    {
        return $this->missionId;
    }

    public function getPlayerId(): int
    {
        return $this->playerId;
    }
}
