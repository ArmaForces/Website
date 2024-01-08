<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use App\Entity\Mod\DirectoryMod;
use App\Form\Mod\Dto\ModFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDirectoryModValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof ModFormDto) {
            throw new UnexpectedTypeException($constraint, ModFormDto::class);
        }

        if (!$constraint instanceof UniqueDirectoryMod) {
            throw new UnexpectedTypeException($constraint, UniqueDirectoryMod::class);
        }

        $directory = $value->getDirectory();
        if ('' === $directory || null === $directory) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(DirectoryMod::class, ['directory' => $directory], $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ directoryName }}' => $directory,
            ],
            $constraint->errorPath
        );
    }
}
