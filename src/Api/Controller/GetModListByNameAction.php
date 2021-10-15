<?php

declare(strict_types=1);

namespace App\Api\Controller;

use App\Entity\ModList\ModListInterface;
use App\Repository\ModList\ModListRepository;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class GetModListByNameAction
{
    public function __construct(
        private ModListRepository $modListRepository
    ) {
    }

    public function __invoke(string $name): ?ModListInterface
    {
        $modList = $this->modListRepository->findOneBy([
            'name' => $name,
        ]);

        if (!$modList) {
            throw new NotFoundHttpException('Not Found');
        }

        return $modList;
    }
}
