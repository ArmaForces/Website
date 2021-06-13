<?php

declare(strict_types=1);

namespace App\Validator\Dlc;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class SteamStoreArma3DlcUrl extends Constraint
{
    public string $invalidDlcUrlMessage = 'Invalid Steam Store DLC url';
    public string $dlcNotFoundMessage = 'DLC not found';
    public string $notAnArma3DlcMessage = 'Url is not an Arma 3 DLC';
    public ?string $errorPath = null;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
