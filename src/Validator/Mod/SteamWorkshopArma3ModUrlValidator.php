<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use App\Form\Mod\Dto\ModFormDto;
use App\Service\SteamWorkshop\Exception\ItemNotFoundException;
use App\Service\SteamWorkshop\Helper\Exception\InvalidItemUrlFormatException;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use App\Service\SteamWorkshop\SteamWorkshopClient;
use App\Validator\AbstractValidator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class SteamWorkshopArma3ModUrlValidator extends AbstractValidator
{
    protected const ARMA_3_GAME_ID = 107410;

    protected SteamWorkshopClient $steamWorkshopClient;

    public function __construct(EntityManagerInterface $entityManager, SteamWorkshopClient $steamWorkshopClient)
    {
        parent::__construct($entityManager);

        $this->steamWorkshopClient = $steamWorkshopClient;
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
            $itemId = SteamWorkshopHelper::itemUrlToItemId($url);
            $itemInfo = $this->steamWorkshopClient->getWorkshopItemInfo($itemId);
        } catch (\Exception $ex) {
            $message = null;

            if ($ex instanceof InvalidItemUrlFormatException) {
                $message = $constraint->invalidModUrlMessage;
            } elseif ($ex instanceof ItemNotFoundException) {
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
