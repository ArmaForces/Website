<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Validator;

use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class WindowsDirectoryName extends Regex
{
    /** @var string */
    public $message = 'Invalid directory name';

    /** @var string */
    public $pattern = '/^.{1,248}[^<>:"\/\|?*]$/ui';

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
