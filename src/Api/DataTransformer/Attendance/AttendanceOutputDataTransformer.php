<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Attendance;

use App\Api\Output\Attendance\AttendanceOutput;
use App\Entity\Attendance\Attendance;

class AttendanceOutputDataTransformer
{
    public function transform(Attendance $attendance): AttendanceOutput
    {
        return new AttendanceOutput(
            $attendance->getId()->toString(),
            $attendance->getMissionId(),
            $attendance->getPlayerId(),
            $attendance->getCreatedAt(),
        );
    }
}
