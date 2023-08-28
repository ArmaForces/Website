<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use App\Entity\Mod\Enum\ModSourceEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Form\Mod\Dto\ModFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ModSourceAndTypeValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof ModFormDto) {
            throw new UnexpectedTypeException($constraint, ModFormDto::class);
        }

        if (!$constraint instanceof ModSourceAndType) {
            throw new UnexpectedTypeException($constraint, ModSourceAndType::class);
        }

        if (ModSourceEnum::DIRECTORY->value === $value->getSource() && ModTypeEnum::SERVER_SIDE->value === $value->getType()) {
            return;
        }

        $this->addViolation($constraint->message, [], $constraint->errorPath);
    }
}
