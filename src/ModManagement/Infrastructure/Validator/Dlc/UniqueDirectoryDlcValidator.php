<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Validator\Dlc;

use App\ModManagement\Domain\Model\Dlc\Dlc;
use App\ModManagement\UserInterface\Http\Form\Dlc\Dto\DlcFormDto;
use App\SharedKernel\Infrastructure\Validator\AbstractValidator;
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
        if (!$directory || $this->isColumnValueUnique(Dlc::class, ['directory' => $directory], $id)) {
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
