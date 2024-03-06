<?php

declare(strict_types=1);

namespace App\Validator\Attendance;

use App\Api\Input\Attendance\AttendanceInput;
use App\Entity\Attendance\Attendance;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueAttendanceValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof AttendanceInput) {
            throw new UnexpectedTypeException($constraint, AttendanceInput::class);
        }

        if (!$constraint instanceof UniqueAttendance) {
            throw new UnexpectedTypeException($constraint, UniqueAttendance::class);
        }

        $missionId = $value->missionId;
        $playerId = $value->playerId;

        if (!$missionId || !$playerId) {
            return;
        }

        if ($this->isColumnValueUnique(Attendance::class, [
            'missionId' => $missionId,
            'playerId' => $playerId,
        ])) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ missionId }}' => $missionId,
                '{{ playerId }}' => $playerId,
            ],
            $constraint->errorPath
        );
    }
}
