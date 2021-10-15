<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class UtilsExtension extends AbstractExtension
{
    public function __construct(
        private RequestStack $requestStack,
        private string $defaultLocale
    ) {
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instance_of', [$this, 'instanceOf']),
        ];
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_current_locale', [$this, 'getCurrentLocale']),
        ];
    }

    public function instanceOf(object $var, string $instance): bool
    {
        return $var instanceof $instance;
    }

    public function getCurrentLocale(): string
    {
        $request = $this->requestStack->getMainRequest();

        // Fallback to default locale if out of request context
        return $request?->getLocale() ?? $this->defaultLocale;
    }
}
