<?php

declare(strict_types=1);

namespace App\Validator\Dlc;

use App\Entity\Dlc\Dlc;
use App\Form\Dlc\Dto\DlcFormDto;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueDirectoryDlcValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof DlcFormDto) {
            throw new UnexpectedTypeException($constraint, DlcFormDto::class);
        }

        if (!$constraint instanceof UniqueDirectoryDlc) {
            throw new UnexpectedTypeException($constraint, UniqueDirectoryDlc::class);
        }

        $directory = $value->getDirectory();
        $id = $value->getId();
        if (!$directory || $this->isColumnValueUnique(Dlc::class, $directory, $id, 'directory')) {
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
