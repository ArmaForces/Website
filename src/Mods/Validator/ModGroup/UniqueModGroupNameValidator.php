<?php

declare(strict_types=1);

namespace App\Mods\Validator\ModGroup;

use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\Form\ModGroup\Dto\ModGroupFormDto;
use App\Shared\Validator\Common\AbstractValidator;
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
        if ('' === $name || null === $name) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(ModGroup::class, ['name' => $name], $id)) {
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
