<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use App\Form\Mod\Dto\ModFormDto;
use App\Service\Steam\Exception\WorkshopItemNotFoundException;
use App\Service\Steam\Helper\Exception\InvalidWorkshopItemUrlFormatException;
use App\Service\Steam\Helper\SteamHelper;
use App\Service\Steam\SteamApiClient;
use App\Validator\AbstractValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SteamWorkshopArma3ModUrlValidator extends AbstractValidator
{
    protected const ARMA_3_GAME_ID = 107410;

    public function __construct(
        EntityManagerInterface $entityManager,
        private SteamApiClient $steamApiClient
    ) {
        parent::__construct($entityManager);
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$value instanceof ModFormDto) {
            throw new UnexpectedTypeException($constraint, ModFormDto::class);
        }

        if (!$constraint instanceof SteamWorkshopArma3ModUrl) {
            throw new UnexpectedTypeException($constraint, SteamWorkshopArma3ModUrl::class);
        }

        $name = $value->getName();
        $url = $value->getUrl();

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
