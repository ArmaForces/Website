<?php

declare(strict_types=1);

namespace App\Mods\Api\Controller\ModList;

use App\Mods\Api\DataTransformer\ModList\ModListDetailsOutputDataTransformer;
use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Repository\ModList\ModListRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class GetModListByNameOperation
{
    public function __construct(
        private ModListRepository $modListRepository,
        private ModListDetailsOutputDataTransformer $modListDetailsOutputDataTransformer,
    ) {
    }

    public function __invoke(string $name): ?ModListOutput
    {
        $modList = $this->modListRepository->findOneByName($name);

        if (!$modList) {
            throw new NotFoundHttpException('Not Found');
        }

        return $this->modListDetailsOutputDataTransformer->transform($modList);
    }
}
