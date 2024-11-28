<?php

declare(strict_types=1);

namespace App\Users\Validator\User;

use App\Mods\Form\ModList\Standard\Dto\StandardModListFormDto;
use App\Shared\Validator\Common\AbstractValidator;
use App\Users\Entity\User\User;
use App\Users\Form\User\Dto\UserFormDto;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueUserSteamIdValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof UserFormDto) {
            throw new UnexpectedTypeException($constraint, StandardModListFormDto::class);
        }

        if (!$constraint instanceof UniqueUserSteamId) {
            throw new UnexpectedTypeException($constraint, UniqueUserSteamId::class);
        }

        $steamId = $value->getSteamId();
        if (null === $steamId) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(User::class, ['steamId' => $steamId], $id)) {
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
