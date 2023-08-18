<?php

declare(strict_types=1);

namespace App\Validator\ModGroup;

use App\Entity\ModGroup\ModGroup;
use App\Form\ModGroup\Dto\ModGroupFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModGroupNameValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof ModGroupFormDto) {
            throw new UnexpectedTypeException($constraint, ModGroupFormDto::class);
        }

        if (!$constraint instanceof UniqueModGroupName) {
            throw new UnexpectedTypeException($constraint, UniqueModGroupName::class);
        }

        $name = $value->getName();
        $id = $value->getId();
        if (!$name || $this->isColumnValueUnique(ModGroup::class, ['name' => $name], $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ modGroupName }}' => $name,
            ],
            $constraint->errorPath
        );
    }
}
