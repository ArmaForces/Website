<?php

declare(strict_types=1);

namespace App\Attendances\Api\DataTransformer\Attendance;

use App\Attendances\Api\Output\Attendance\AttendanceOutput;
use App\Attendances\Entity\Attendance\Attendance;

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
