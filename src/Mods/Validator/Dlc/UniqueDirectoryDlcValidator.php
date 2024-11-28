<?php

declare(strict_types=1);

namespace App\Mods\Validator\Dlc;

use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Form\Dlc\Dto\DlcFormDto;
use App\Shared\Validator\Common\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDirectoryDlcValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof DlcFormDto) {
            throw new UnexpectedTypeException($constraint, DlcFormDto::class);
        }

        if (!$constraint instanceof UniqueDirectoryDlc) {
            throw new UnexpectedTypeException($constraint, UniqueDirectoryDlc::class);
        }

        $directory = $value->getDirectory();
        if ('' === $directory || null === $directory) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(Dlc::class, ['directory' => $directory], $id)) {
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
