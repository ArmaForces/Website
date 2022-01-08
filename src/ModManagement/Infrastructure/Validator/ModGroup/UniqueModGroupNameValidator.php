<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Validator\ModGroup;

use App\ModManagement\Domain\Model\ModGroup\ModGroup;
use App\ModManagement\UserInterface\Http\Form\ModGroup\Dto\ModGroupFormDto;
use App\SharedKernel\Infrastructure\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModGroupNameValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
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
