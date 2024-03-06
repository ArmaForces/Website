<?php

declare(strict_types=1);

namespace App\Api\Provider\ModList;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use App\Api\DataTransformer\ModList\ModListOutputDataTransformer;
use App\Api\Provider\Common\AbstractDataProvider;

class ModListDataProvider extends AbstractDataProvider
{
    public function __construct(
        ItemProvider $itemProvider,
        CollectionProvider $collectionProvider,
        private ModListOutputDataTransformer $modListOutputDataTransformer
    ) {
        parent::__construct($itemProvider, $collectionProvider);
    }

    public function provideTransformedData(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object {
        return $this->modListOutputDataTransformer->transform($data);
    }
}
