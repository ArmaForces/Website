<?php

declare(strict_types=1);

namespace App\Validator\UserGroup;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserGroupName extends Constraint
{
    /** @var string */
    public $message = 'User group with the same name "{{ userGroupName }}" already exist';

    /** @var null|string */
    public $errorPath;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
