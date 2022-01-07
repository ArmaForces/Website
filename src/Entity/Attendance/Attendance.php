<?php

declare(strict_types=1);

namespace App\Entity\Attendance;

use App\SharedKernel\Domain\Model\AbstractEntity;
use Ramsey\Uuid\UuidInterface;

class Attendance extends AbstractEntity implements AttendanceInterface
{
    public function __construct(
        UuidInterface $id,
        private string $missionId,
        private int $playerId
    ) {
        parent::__construct($id);
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
