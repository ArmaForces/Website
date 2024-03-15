<?php

declare(strict_types=1);

namespace App\Mods\Api\Provider\ModList;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use App\Mods\Api\DataTransformer\ModList\ModListOutputDataTransformer;
use App\Shared\Api\Provider\Common\AbstractDataProvider;

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
