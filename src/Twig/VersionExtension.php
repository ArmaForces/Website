<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\Version\VersionProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VersionExtension extends AbstractExtension
{
    /** @var VersionProvider */
    protected $version;

    public function __construct(VersionProvider $version)
    {
        $this->version = $version;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_version', [$this, 'getVersion'], [
                'is_safe' => ['html'],
            ]),
        ];
    }

    public function getVersion(): string
    {
        $version = $this->version->getVersion();

        if ('dev' !== $version) {
            return "<a href='https://github.com/ArmaForces/Website/commit/{$version}'>".substr($version, 0, 8).'</a>';
        }

        return $version;
    }
}
