<?php

declare(strict_types=1);

namespace App\Attendances\Validator\Attendance;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class UniqueAttendance extends Constraint
{
    public string $message = 'Attendance of player "{{ playerId }}" in mission "{{ missionId }}" already exists.';
    public ?string $errorPath = null;

    public function __construct(
        string $message = null,
        string $errorPath = null,
        $options = null,
        array $groups = null,
        $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->message = $message ?? $this->message;
        $this->errorPath = $errorPath ?? $this->errorPath;
    }

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
