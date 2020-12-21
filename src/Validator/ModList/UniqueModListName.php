<?php

declare(strict_types=1);

namespace App\Validator\ModList;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueModListName extends Constraint
{
    /** @var string */
    public $message = 'Mod list with the same name "{{ modListName }}" already exist';

    /** @var null|string */
    public $errorPath;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
