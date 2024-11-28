<?php

declare(strict_types=1);

namespace App\Mods\Validator\Mod;

use App\Mods\Form\Mod\Dto\ModFormDto;
use App\Shared\Service\SteamApiClient\Exception\WorkshopItemNotFoundException;
use App\Shared\Service\SteamApiClient\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\Shared\Service\SteamApiClient\Helper\SteamHelper;
use App\Shared\Service\SteamApiClient\SteamApiClientInterface;
use App\Shared\Validator\Common\AbstractValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SteamWorkshopArma3ModUrlValidator extends AbstractValidator
{
    private const ARMA_3_GAME_ID = 107410;

    public function __construct(
        EntityManagerInterface $entityManager,
        private SteamApiClientInterface $steamApiClient
    ) {
        parent::__construct($entityManager);
    }

    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$value instanceof ModFormDto) {
            throw new UnexpectedTypeException($constraint, ModFormDto::class);
        }

        if (!$constraint instanceof SteamWorkshopArma3ModUrl) {
            throw new UnexpectedTypeException($constraint, SteamWorkshopArma3ModUrl::class);
        }

        $name = $value->getName();
        $url = $value->getUrl();
        if ('' === $url || null === $url) {
            return;
        }

        try {
            $itemId = SteamHelper::itemUrlToItemId($url);
            $itemInfo = $this->steamApiClient->getWorkshopItemInfo($itemId);
        } catch (\Exception $ex) {
            $message = null;

            if ($ex instanceof InvalidWorkshopItemUrlFormatException) {
                $message = $constraint->invalidModUrlMessage;
            } elseif ($ex instanceof WorkshopItemNotFoundException) {
                $message = $constraint->modNotFoundMessage;
            }

            $this->addViolation($message, [], $constraint->errorPath);

            return;
        }

        if ($itemInfo->getGameId() && self::ARMA_3_GAME_ID !== $itemInfo->getGameId()) {
            $this->addViolation($constraint->notAnArma3ModMessage, [], $constraint->errorPath);
        }

        if (!$name && !$itemInfo->getName()) {
            $this->addViolation($constraint->modIsPrivateOrMissingDetails, [], $constraint->nameErrorPath);
        }
    }
}
