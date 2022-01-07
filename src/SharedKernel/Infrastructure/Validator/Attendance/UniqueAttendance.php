<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Validator\Attendance;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueAttendance extends Constraint
{
    public string $message = 'Attendance of player "{{ playerId }}" in mission "{{ missionId }}" already exists';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
