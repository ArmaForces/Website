<?php

declare(strict_types=1);

namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigTest;

class UtilsExtension extends AbstractExtension
{
    public function getTests(): array
    {
        return [
            new TwigTest('instance_of', [$this, 'instanceOf']),
        ];
    }

    /**
     * @param object $var
     */
    public function instanceOf($var, string $instance): bool
    {
        return $var instanceof $instance;
    }
}
