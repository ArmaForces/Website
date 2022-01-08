<?php

declare(strict_types=1);

namespace App\ModManagement\UserInterface\Http\Api\Controller;

use App\ModManagement\Domain\Model\ModList\ModListInterface;
use App\ModManagement\Infrastructure\Persistence\ModList\ModListRepository;
use Symfony\Component\HttpKernel\Attribute\AsController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

#[AsController]
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
