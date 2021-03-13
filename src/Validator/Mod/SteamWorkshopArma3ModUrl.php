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

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
