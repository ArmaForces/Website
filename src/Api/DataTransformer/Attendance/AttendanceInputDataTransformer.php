<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Attendance;

use ApiPlatform\Validator\ValidatorInterface;
use App\Api\Input\Attendance\AttendanceInput;
use App\Entity\Attendance\Attendance;
use Ramsey\Uuid\Uuid;

class AttendanceInputDataTransformer
{
    public function __construct(
        private ValidatorInterface $validator
    ) {
    }

    public function transform(AttendanceInput $attendanceInput): Attendance
    {
        $this->validator->validate($attendanceInput);

        return new Attendance(
            Uuid::uuid4(),
            $attendanceInput->missionId,
            $attendanceInput->playerId
        );
    }
}
