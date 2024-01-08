<?php

declare(strict_types=1);

namespace App\Validator\ModList;

use App\Entity\ModList\ModList;
use App\Form\ModList\Dto\ModListFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModListNameValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof ModListFormDto) {
            throw new UnexpectedTypeException($constraint, ModListFormDto::class);
        }

        if (!$constraint instanceof UniqueModListName) {
            throw new UnexpectedTypeException($constraint, UniqueModListName::class);
        }

        $name = $value->getName();
        if ('' === $name || null === $name) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(ModList::class, ['name' => $name], $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ modListName }}' => $name,
            ],
            $constraint->errorPath
        );
    }
}
