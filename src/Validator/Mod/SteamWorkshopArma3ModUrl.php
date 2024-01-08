<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use Symfony\Component\Validator\Constraint;

#[\Attribute(\Attribute::TARGET_CLASS | \Attribute::IS_REPEATABLE)]
class SteamWorkshopArma3ModUrl extends Constraint
{
    public string $invalidModUrlMessage = 'Invalid Steam Workshop mod url.';
    public string $modNotFoundMessage = 'Mod not found.';
    public string $notAnArma3ModMessage = 'Url is not an Arma 3 mod.';
    public string $modIsPrivateOrMissingDetails = 'Mod is either private or missing required details.';
    public ?string $errorPath = null;
    public ?string $nameErrorPath = null;

    public function __construct(
        string $invalidModUrlMessage = null,
        string $modNotFoundMessage = null,
        string $notAnArma3ModMessage = null,
        string $modIsPrivateOrMissingDetails = null,
        string $errorPath = null,
        string $nameErrorPath = null,
        $options = null,
        array $groups = null,
        $payload = null
    ) {
        parent::__construct($options, $groups, $payload);

        $this->invalidModUrlMessage = $invalidModUrlMessage ?? $this->invalidModUrlMessage;
        $this->modNotFoundMessage = $modNotFoundMessage ?? $this->modNotFoundMessage;
        $this->notAnArma3ModMessage = $notAnArma3ModMessage ?? $this->notAnArma3ModMessage;
        $this->modIsPrivateOrMissingDetails = $modIsPrivateOrMissingDetails ?? $this->modIsPrivateOrMissingDetails;
        $this->errorPath = $errorPath ?? $this->errorPath;
        $this->nameErrorPath = $nameErrorPath ?? $this->nameErrorPath;
    }

    public function getTargets(): array|string
    {
        return parent::CLASS_CONSTRAINT;
    }
}
