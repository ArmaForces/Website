<?php

declare(strict_types=1);

namespace App\Mods\Api\Controller\ModList;

use App\Mods\Api\DataTransformer\ModList\ModListDetailsOutputDataTransformer;
use App\Mods\Api\Output\ModList\ModListOutput;
use App\Mods\Repository\ModList\StandardModListRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
class GetModListByNameOperation
{
    public function __construct(
        private StandardModListRepository $standardModListRepository,
        private ModListDetailsOutputDataTransformer $modListDetailsOutputDataTransformer,
    ) {
    }

    public function __invoke(string $name): ?ModListOutput
    {
        $standardModList = $this->standardModListRepository->findOneByName($name);

        if (!$standardModList) {
            throw new NotFoundHttpException('Not Found');
        }

        return $this->modListDetailsOutputDataTransformer->transform($standardModList);
    }
}
