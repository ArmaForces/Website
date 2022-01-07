<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Validator\User;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserSteamId extends Constraint
{
    public string $message = 'User with the same Steam ID "{{ userSteamId }}" already exist';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
