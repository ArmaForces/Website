<?php

declare(strict_types=1);

namespace App\Cache\CacheWarmer;

use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpKernel\CacheWarmer\CacheWarmer;

class BackgroundImageCacheWarmer extends CacheWarmer
{
    /** @var string */
    protected $backgroundImagesDirectory;

    public function __construct(string $backgroundImagesDirectory)
    {
        $this->backgroundImagesDirectory = $backgroundImagesDirectory;
    }

    public static function getCacheFileName(): string
    {
        return 'background_images.php';
    }

    /**
     * @param string $cacheDir
     */
    public function warmUp($cacheDir): void
    {
        $fileTemplate = '<?php return %s;';
        $this->writeCacheFile(
            $cacheDir.'/'.static::getCacheFileName(),
            sprintf($fileTemplate, var_export($this->findImagesToCache(), true))
        );
    }

    public function isOptional(): bool
    {
        return false;
    }

    protected function findImagesToCache(): array
    {
        $imagesIterator = (Finder::create())->in($this->backgroundImagesDirectory)->files()->getIterator();

        $images = array_map(static function (\SplFileInfo $imageFile) {
            return $imageFile->getFilename();
        }, iterator_to_array($imagesIterator));

        return array_values($images);
    }
}
