<?php

declare(strict_types=1);

namespace App\Validator\ModTag;

use App\Entity\ModTag\ModTag;
use App\Form\ModTag\Dto\ModTagFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModTagNameValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof ModTagFormDto) {
            throw new UnexpectedTypeException($constraint, ModTagFormDto::class);
        }

        if (!$constraint instanceof UniqueModTagName) {
            throw new UnexpectedTypeException($constraint, UniqueModTagName::class);
        }

        $name = $value->getName();
        $id = $value->getId();
        if (!$name || $this->isColumnValueUnique(ModTag::class, $name, $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ modTagName }}' => $name,
            ],
            $constraint->errorPath
        );
    }
}
