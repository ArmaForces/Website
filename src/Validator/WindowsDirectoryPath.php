<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraints\Regex;

/**
 * @Annotation
 */
class WindowsDirectoryPath extends Regex
{
    /** @var string */
    public $message = 'Invalid directory path';

    /** @var string */
    public $pattern = '/^[a-zA-Z]:\\\\(((?![<>:"\/\\\\|?*]).)+((?<![ .])\\\\)?)*$/';

    /**
     * @return string[]
     */
    public function getRequiredOptions(): array
    {
        return [];
    }
}
