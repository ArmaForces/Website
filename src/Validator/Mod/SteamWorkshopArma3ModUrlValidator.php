<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use App\Service\SteamWorkshop\Exception\ItemNotFoundException;
use App\Service\SteamWorkshop\Helper\Exception\InvalidItemUrlFormatException;
use App\Service\SteamWorkshop\Helper\SteamWorkshopHelper;
use App\Service\SteamWorkshop\SteamWorkshopClient;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

class SteamWorkshopArma3ModUrlValidator extends ConstraintValidator
{
    protected const ARMA_3_GAME_ID = 107410;

    protected SteamWorkshopClient $steamWorkshopClient;

    public function __construct(SteamWorkshopClient $steamWorkshopClient)
    {
        $this->steamWorkshopClient = $steamWorkshopClient;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof SteamWorkshopArma3ModUrl) {
            throw new UnexpectedTypeException($constraint, SteamWorkshopArma3ModUrl::class);
        }

        if (null === $value || '' === $value) {
            return;
        }

        if (!is_scalar($value) && !(\is_object($value) && method_exists($value, '__toString'))) {
            throw new UnexpectedValueException($value, 'string');
        }

        $value = (string) $value;

        try {
            $itemId = SteamWorkshopHelper::itemUrlToItemId($value);
            $itemInfo = $this->steamWorkshopClient->getWorkshopItemInfo($itemId);
        } catch (\Exception $ex) {
            $message = null;

            if ($ex instanceof InvalidItemUrlFormatException) {
                $message = $constraint->invalidModUrlMessage;
            } elseif ($ex instanceof ItemNotFoundException) {
                $message = $constraint->modNotFoundMessage;
            }

            $this->context->buildViolation($message)
                ->addViolation()
            ;

            return;
        }

        if (self::ARMA_3_GAME_ID !== $itemInfo->getGameId()) {
            $this->context->buildViolation($constraint->notAnArma3ModMessage)
                ->addViolation()
            ;
        }
    }
}
