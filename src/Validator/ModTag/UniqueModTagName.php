<?php

declare(strict_types=1);

namespace App\Validator\ModTag;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueModTagName extends Constraint
{
    /** @var string */
    public $message = 'Mod tag with the same name "{{ modTagName }}" already exist';

    /** @var null|string */
    public $errorPath;

    public function getTargets()
    {
        return parent::CLASS_CONSTRAINT;
    }
}
