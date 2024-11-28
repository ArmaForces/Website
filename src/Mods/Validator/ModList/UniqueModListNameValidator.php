<?php

declare(strict_types=1);

namespace App\Mods\Validator\ModList;

use App\Mods\Entity\ModList\AbstractModList;
use App\Mods\Form\ModList\External\Dto\ExternalModListFormDto;
use App\Mods\Form\ModList\Standard\Dto\StandardModListFormDto;
use App\Shared\Validator\Common\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueModListNameValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof StandardModListFormDto && !$value instanceof ExternalModListFormDto) {
            throw new UnexpectedTypeException($constraint, sprintf('%s|%s', StandardModListFormDto::class, ExternalModListFormDto::class));
        }

        if (!$constraint instanceof UniqueModListName) {
            throw new UnexpectedTypeException($constraint, UniqueModListName::class);
        }

        $name = $value->getName();
        if ('' === $name || null === $name) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(AbstractModList::class, ['name' => $name], $id)) {
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
