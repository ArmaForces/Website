<?php

declare(strict_types=1);

namespace App\Validator\Mod;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueDirectoryMod extends Constraint
{
    /** @var string */
    public $message = 'Mod associated with directory "{{ directoryName }}" already exist';

    /** @var null|string */
    public $errorPath;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
