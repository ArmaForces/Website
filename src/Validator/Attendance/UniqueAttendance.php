<?php

declare(strict_types=1);

namespace App\Validator\Attendance;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueAttendance extends Constraint
{
    public string $message = 'Attendance of player "{{ playerId }}" in mission "{{ missionId }}" already exist';
    public ?string $errorPath = null;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
