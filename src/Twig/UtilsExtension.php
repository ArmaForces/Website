<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class UtilsExtension extends AbstractExtension
{
    protected ParameterBagInterface $parameterBag;
    protected RequestStack $requestStack;

    public function __construct(ParameterBagInterface $parameterBag, RequestStack $requestStack)
    {
        $this->parameterBag = $parameterBag;
        $this->requestStack = $requestStack;
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

    /**
     * @param object $var
     */
    public function instanceOf($var, string $instance): bool
    {
        return $var instanceof $instance;
    }

    public function getCurrentLocale(): string
    {
        $request = $this->requestStack->getMasterRequest();

        // Fallback to default locale if out of request context
        if (!$request) {
            return $this->parameterBag->get('kernel.default_locale');
        }

        return $request->getLocale();
    }
}
