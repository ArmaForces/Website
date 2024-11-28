<?php

declare(strict_types=1);

namespace App\Users\Validator\UserGroup;

use App\Shared\Validator\Common\AbstractValidator;
use App\Users\Entity\UserGroup\UserGroup;
use App\Users\Form\UserGroup\Dto\UserGroupFormDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserGroupNameValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof UserGroupFormDto) {
            throw new UnexpectedTypeException($constraint, UserGroupFormDto::class);
        }

        if (!$constraint instanceof UniqueUserGroupName) {
            throw new UnexpectedTypeException($constraint, UniqueUserGroupName::class);
        }

        $name = $value->getName();
        if ('' === $name || null === $name) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(UserGroup::class, ['name' => $name], $id)) {
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
