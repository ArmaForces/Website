<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Twig;

use App\SharedKernel\Infrastructure\Service\Version\VersionProvider;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class VersionExtension extends AbstractExtension
{
    public function __construct(
        private VersionProvider $version
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('app_version', [$this, 'getVersion']),
            new TwigFunction('app_version_short', [$this, 'getVersionShort']),
        ];
    }

    public function getVersion(): string
    {
        return $this->version->getVersion();
    }

    public function getVersionShort(): string
    {
        return substr($this->getVersion(), 0, 8);
    }
}
