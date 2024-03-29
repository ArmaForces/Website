<?php

declare(strict_types=1);

namespace App\Mods\Validator\Mod;

use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Mods\Form\Mod\Dto\ModFormDto;
use App\Shared\Service\SteamApiClient\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\Shared\Service\SteamApiClient\Helper\SteamHelper;
use App\Shared\Validator\Common\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSteamWorkshopModValidator extends AbstractValidator
{
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof ModFormDto) {
            throw new UnexpectedTypeException($constraint, ModFormDto::class);
        }

        if (!$constraint instanceof UniqueSteamWorkshopMod) {
            throw new UnexpectedTypeException($constraint, UniqueSteamWorkshopMod::class);
        }

        $url = $value->getUrl();
        if (!$url) {
            return;
        }

        try {
            $itemId = SteamHelper::itemUrlToItemId($url);
        } catch (InvalidWorkshopItemUrlFormatException $ex) {
            return;
        }

        $id = $value->getId();
        if ($this->isColumnValueUnique(SteamWorkshopMod::class, ['itemId' => (string) $itemId], $id)) {
            return;
        }

        $this->addViolation(
            $constraint->message,
            [
                '{{ modUrl }}' => $url,
            ],
            $constraint->errorPath
        );
    }
}
