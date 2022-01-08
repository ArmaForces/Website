<?php

declare(strict_types=1);

namespace App\Attendance\Infrastructure\Validator\Attendance;

use App\Attendance\Domain\Model\Attendance\Attendance;
use App\Attendance\UserInterface\Http\Api\Input\Attendance\AttendanceInput;
use App\SharedKernel\Infrastructure\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueAttendanceValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof AttendanceInput) {
            throw new UnexpectedTypeException($constraint, AttendanceInput::class);
        }

        if (!$constraint instanceof UniqueAttendance) {
            throw new UnexpectedTypeException($constraint, UniqueAttendance::class);
        }

        $missionId = $value->getMissionId();
        $playerId = $value->getPlayerId();

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
