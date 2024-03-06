<?php

declare(strict_types=1);

namespace App\Api\DataTransformer\Attendance;

use ApiPlatform\Validator\ValidatorInterface;
use App\Api\Input\Attendance\AttendanceInput;
use App\Entity\Attendance\Attendance;
use App\Service\IdentifierFactory\IdentifierFactoryInterface;

class AttendanceInputDataTransformer
{
    public function __construct(
        private ValidatorInterface $validator,
        private IdentifierFactoryInterface $identifierFactory
    ) {
    }

    public function transform(AttendanceInput $attendanceInput): Attendance
    {
        $this->validator->validate($attendanceInput);

        return new Attendance(
            $this->identifierFactory->create(),
            $attendanceInput->missionId,
            $attendanceInput->playerId
        );
    }
}
