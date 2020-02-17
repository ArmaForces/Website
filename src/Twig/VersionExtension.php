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
