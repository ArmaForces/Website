<?php

declare(strict_types=1);

namespace App\Shared\Twig;

use Symfony\Component\Finder\Finder;
use Symfony\Contracts\Cache\CacheInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class BackgroundImageExtension extends AbstractExtension
{
    public function __construct(
        private string $backgroundImagesDirectory,
        private CacheInterface $cacheAdapter,
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
        return $this->cacheAdapter->get('background_images', function (ItemInterface $item): array {
            $backgroundImages = $this->findBackgroundImagesToCache();
            $item->set($backgroundImages);

            return $backgroundImages;
        });
    }

    private function findBackgroundImagesToCache(): array
    {
        $imagesIterator = Finder::create()->in($this->backgroundImagesDirectory)->files()->getIterator();

        $images = array_map(static fn (\SplFileInfo $imageFile) => $imageFile->getFilename(), iterator_to_array($imagesIterator));

        return array_values($images);
    }
}
