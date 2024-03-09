<?php

declare(strict_types=1);

namespace App\Shared\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class ImgproxyExtension extends AbstractExtension
{
    public function __construct(
        private string $baseProxyUrl,
        private string $proxyKey,
        private string $proxySalt
    ) {
    }

    public function getFunctions(): array
    {
        return [
            new TwigFunction('proxy_image', [$this, 'getProxyImageUrl']),
        ];
    }

    /**
     * @param string $url     Image url
     * @param int    $width   Target width, set to 0 to automatically calculate from height
     * @param int    $height  Target height, set to 0 to automatically calculate from width
     * @param bool   $enlarge Should resized image be enlarged if it's smaller than target size
     *
     * @throws \Exception
     */
    public function getProxyImageUrl(string $url, int $width = 0, int $height = 250, bool $enlarge = true): string
    {
        $encodedUrl = rtrim(strtr(base64_encode($url), '+/', '-_'), '=');

        $resizingType = 'fit';
        $enlargeInt = (int) $enlarge;

        $path = "/resize:{$resizingType}:{$width}:{$height}:{$enlargeInt}/{$encodedUrl}";
        $signature = $this->getSignature($path);

        return sprintf('%s/%s%s', $this->baseProxyUrl, $signature, $path);
    }

    /**
     * https://github.com/imgproxy/imgproxy/blob/master/examples/signature.php.
     */
    private function getSignature(string $path): string
    {
        $binaryKey = pack('H*', $this->proxyKey);
        if (empty($binaryKey)) {
            throw new \Exception('Key expected to be hex-encoded string');
        }

        $binarySalt = pack('H*', $this->proxySalt);
        if (empty($binarySalt)) {
            throw new \Exception('Salt expected to be hex-encoded string');
        }

        return rtrim(strtr(base64_encode(hash_hmac('sha256', $binarySalt.$path, $binaryKey, true)), '+/', '-_'), '=');
    }
}
