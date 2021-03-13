<?php

declare(strict_types=1);

namespace App\Validator\UserGroup;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserGroupName extends Constraint
{
    public string $message = 'User group with the same name "{{ userGroupName }}" already exist';
    public ?string $errorPath = null;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
