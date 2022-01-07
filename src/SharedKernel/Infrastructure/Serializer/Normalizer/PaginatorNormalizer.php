<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Serializer\Normalizer;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginatorNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    public function normalize($object, string $format = null, array $context = []): array
    {
        /** @var Paginator $object */
        $data = [];
        foreach ($object->getIterator() as $item) {
            $data[] = $this->normalizer->normalize($item, $format, $context);
        }

        return [
            'data' => $data,
            'items' => $object->count(),
            'totalItems' => $object->getTotalItems(),
            'currentPage' => $object->getCurrentPage(),
            'lastPage' => $object->getLastPage(),
            'itemsPerPage' => $object->getItemsPerPage(),
        ];
    }

    public function supportsNormalization($data, string $format = null): bool
    {
        return $data instanceof Paginator && 'json' === $format;
    }
}
