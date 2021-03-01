<?php

declare(strict_types=1);

namespace App\Twig;

use App\Cache\CacheWarmer\BackgroundImageCacheWarmer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BackgroundImageExtension extends AbstractExtension
{
    protected string $cacheDir;

    public function __construct(string $cacheDir)
    {
        $this->cacheDir = $cacheDir;
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('get_background_images', [$this, 'getBackgroundImages']),
        ];
    }

    public function getBackgroundImages(): array
    {
        $filePath = $this->cacheDir.'/'.BackgroundImageCacheWarmer::getCacheFileName();

        return include $filePath;
    }
}
