<?php

declare(strict_types=1);

namespace App\Twig;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use Twig\TwigTest;

class UtilsExtension extends AbstractExtension
{
    /** @var ParameterBagInterface */
    protected $parameterBag;

    public function __construct(ParameterBagInterface $parameterBag)
    {
        $this->parameterBag = $parameterBag;
    }

    public function getTests(): array
    {
        return [
            new TwigTest('instance_of', [$this, 'instanceOf']),
        ];
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('get_default_locale', [$this, 'getDefaultLocale']),
        ];
    }

    /**
     * @param object $var
     */
    public function instanceOf($var, string $instance): bool
    {
        return $var instanceof $instance;
    }

    public function getDefaultLocale(): string
    {
        return $this->parameterBag->get('kernel.default_locale');
    }
}
