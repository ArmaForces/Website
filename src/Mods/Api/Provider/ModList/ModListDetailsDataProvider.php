<?php

declare(strict_types=1);

namespace App\Mods\Api\Provider\ModList;

use ApiPlatform\Doctrine\Orm\State\CollectionProvider;
use ApiPlatform\Doctrine\Orm\State\ItemProvider;
use ApiPlatform\Metadata\Operation;
use App\Mods\Api\DataTransformer\ModList\ModListDetailsOutputDataTransformer;
use App\Shared\Api\Provider\Common\AbstractDataProvider;

class ModListDetailsDataProvider extends AbstractDataProvider
{
    public function __construct(
        ItemProvider $itemProvider,
        CollectionProvider $collectionProvider,
        private ModListDetailsOutputDataTransformer $modListDetailsOutputDataTransformer
    ) {
        parent::__construct($itemProvider, $collectionProvider);
    }

    public function provideTransformedData(
        mixed $data,
        Operation $operation,
        array $uriVariables = [],
        array $context = []
    ): object {
        return $this->modListDetailsOutputDataTransformer->transform($data);
    }
}
