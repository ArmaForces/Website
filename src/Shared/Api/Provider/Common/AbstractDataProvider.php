<?php

declare(strict_types=1);

namespace App\Shared\Api\Provider\Common;

use ApiPlatform\Doctrine\Orm\Paginator;
use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\CollectionOperationInterface;
use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\Pagination;
use ApiPlatform\State\ProviderInterface;

class AbstractDataProvider implements ProviderInterface
{
    public function __construct(
        private readonly ItemProvider $itemProvider,
        private readonly CollectionProvider $collectionProvider
    ) {
    }

    public function provide(Operation $operation, array $uriVariables = [], array $context = []): object|array|null
    {
        if ($operation instanceof CollectionOperationInterface) {
            /** @var Paginator $paginator */
            $paginator = $this->collectionProvider->provide($operation, $uriVariables, $context);

            return new Pagination\TraversablePaginator(
                $this->transformCollection($paginator->getIterator(), $operation, $uriVariables, $context),
                $paginator->getCurrentPage(),
                $paginator->getItemsPerPage(),
                $paginator->getTotalItems(),
            );
        }

        $item = $this->itemProvider->provide($operation, $uriVariables, $context);
        if (null !== $item) {
            $item = $this->provideTransformedData($item, $operation, $uriVariables, $context);
        }

        return $item;
    }

    public function provideTransformedData(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object {
        return $data;
    }

    private function transformCollection(
        \Traversable $collection,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): \Traversable {
        foreach ($collection as $item) {
            yield $this->provideTransformedData($item, $operation, $uriVariables, $context);
        }
    }
}
