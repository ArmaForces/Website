<?php

declare(strict_types=1);

namespace App\Validator\Dlc;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class SteamStoreArma3DlcUrl extends Constraint
{
    public string $invalidDlcUrlMessage = 'Invalid Steam Store DLC url.';
    public string $dlcNotFoundMessage = 'DLC not found.';
    public string $notAnArma3DlcMessage = 'Url is not an Arma 3 DLC.';
    public ?string $errorPath = null;

    public function __construct(
        string $invalidDlcUrlMessage = null,
        string $dlcNotFoundMessage = null,
        string $notAnArma3DlcMessage = null,
        string $errorPath = null,
        $options = null,
        array $groups = null,
        $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->invalidDlcUrlMessage = $invalidDlcUrlMessage ?? $this->invalidDlcUrlMessage;
        $this->dlcNotFoundMessage = $dlcNotFoundMessage ?? $this->dlcNotFoundMessage;
        $this->notAnArma3DlcMessage = $notAnArma3DlcMessage ?? $this->notAnArma3DlcMessage;
        $this->errorPath = $errorPath ?? $this->errorPath;
    }

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
