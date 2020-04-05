<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueSteamWorkshopMod extends Constraint
{
    /** @var string */
    public $message = 'Mod associated with url "{{ modUrl }}" already exist';

    /** @var null|string */
    public $errorPath;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
