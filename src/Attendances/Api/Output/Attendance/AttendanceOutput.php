<?php

declare(strict_types=1);

namespace App\Attendances\Api\Output\Attendance;

class AttendanceOutput
{
    public function __construct(
        public string $id,
        public string $missionId,
        public int $playerId,
        public ?\DateTimeInterface $createdAt,
    ) {
    }
}
