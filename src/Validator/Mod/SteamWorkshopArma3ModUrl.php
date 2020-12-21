<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SteamWorkshopArma3ModUrl extends Constraint
{
    /** @var string */
    public $invalidModUrlMessage = 'Invalid Steam Workshop mod URL';

    /** @var string */
    public $modNotFoundMessage = 'Mod not found';

    /** @var string */
    public $notAnArma3ModMessage = 'Mod is not an Arma 3 mod';

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
