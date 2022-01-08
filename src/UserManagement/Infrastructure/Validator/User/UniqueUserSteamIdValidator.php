<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Validator\User;

use App\Form\ModList\Dto\ModListFormDto;
use App\SharedKernel\Infrastructure\Validator\AbstractValidator;
use App\UserManagement\Domain\Model\User\User;
use App\UserManagement\UserInterface\Http\Form\User\Dto\UserFormDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserSteamIdValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof UserFormDto) {
            throw new UnexpectedTypeException($constraint, ModListFormDto::class);
        }

        if (!$constraint instanceof UniqueUserSteamId) {
            throw new UnexpectedTypeException($constraint, UniqueUserSteamId::class);
        }

        $steamId = $value->getSteamId();
        $id = $value->getId();
        if (!$steamId || $this->isColumnValueUnique(User::class, ['steamId' => $steamId], $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ userSteamId }}' => $steamId,
            ],
            $constraint->errorPath
        );
    }
}
