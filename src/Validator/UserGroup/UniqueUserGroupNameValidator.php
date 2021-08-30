<?php

declare(strict_types=1);

namespace App\Validator\UserGroup;

use App\Entity\UserGroup\UserGroup;
use App\Form\UserGroup\Dto\UserGroupFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserGroupNameValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof UserGroupFormDto) {
            throw new UnexpectedTypeException($constraint, UserGroupFormDto::class);
        }

        if (!$constraint instanceof UniqueUserGroupName) {
            throw new UnexpectedTypeException($constraint, UniqueUserGroupName::class);
        }

        $name = $value->getName();
        $id = $value->getId();
        if (!$name || $this->isColumnValueUnique(UserGroup::class, ['name' => $name], $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ userGroupName }}' => $name,
            ],
            $constraint->errorPath
        );
    }
}
