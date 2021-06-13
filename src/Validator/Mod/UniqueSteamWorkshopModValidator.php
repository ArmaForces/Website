<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use App\Entity\Mod\SteamWorkshopMod;
use App\Form\Mod\Dto\ModFormDto;
use App\Service\Steam\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\Service\Steam\Helper\SteamHelper;
use App\Validator\AbstractValidator;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class UniqueSteamWorkshopModValidator extends AbstractValidator
{
    public function validate($value, Constraint $constraint): void
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
        if ($this->isColumnValueUnique(SteamWorkshopMod::class, (string) $itemId, $id, 'itemId')) {
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
