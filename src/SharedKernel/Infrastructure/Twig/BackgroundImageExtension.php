<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Twig;

use App\SharedKernel\Infrastructure\Cache\CacheWarmer\BackgroundImageCacheWarmer;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BackgroundImageExtension extends AbstractExtension
{
    public function __construct(
        private string $cacheDir
    ) {
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
