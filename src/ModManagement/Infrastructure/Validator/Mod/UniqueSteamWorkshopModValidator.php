<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Validator\Mod;

use App\ModManagement\Domain\Model\Mod\SteamWorkshopMod;
use App\ModManagement\UserInterface\Http\Form\Mod\Dto\ModFormDto;
use App\SharedKernel\Infrastructure\Service\Steam\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\SharedKernel\Infrastructure\Service\Steam\Helper\SteamHelper;
use App\SharedKernel\Infrastructure\Validator\AbstractValidator;
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
