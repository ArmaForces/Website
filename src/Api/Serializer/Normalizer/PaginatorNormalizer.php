<?php

declare(strict_types=1);

namespace App\Api\Serializer\Normalizer;

use ApiPlatform\State\Pagination\TraversablePaginator;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareInterface;
use Symfony\Component\Serializer\Normalizer\NormalizerAwareTrait;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

class PaginatorNormalizer implements NormalizerInterface, NormalizerAwareInterface
{
    use NormalizerAwareTrait;

    /**
     * @param TraversablePaginator $object
     */
    public function normalize(mixed $object, string $format = null, array $context = []): array
    {
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

    public function supportsNormalization(mixed $data, string $format = null): bool
    {
        return $data instanceof TraversablePaginator && 'json' === $format;
    }
}
