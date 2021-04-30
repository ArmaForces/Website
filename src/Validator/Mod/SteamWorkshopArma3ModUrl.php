<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SteamWorkshopArma3ModUrl extends Constraint
{
    public string $invalidModUrlMessage = 'Invalid Steam Workshop mod URL';
    public string $modNotFoundMessage = 'Mod not found';
    public string $notAnArma3ModMessage = 'Mod is not an Arma 3 mod';
    public string $modIsPrivateOrMissingDetails = 'Mod is either private or missing required details';
    public ?string $errorPath = null;
    public ?string $nameErrorPath = null;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
