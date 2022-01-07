<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Validator\UserGroup;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserGroupName extends Constraint
{
    public string $message = 'User group with the same name "{{ userGroupName }}" already exist';
    public ?string $errorPath = null;

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
