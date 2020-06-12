<?php

declare(strict_types=1);

namespace App\Api\DataProvider;

use ApiPlatform\Core\DataProvider\ItemDataProviderInterface;
use ApiPlatform\Core\DataProvider\RestrictedDataProviderInterface;
use App\Entity\ModList\ModList;
use App\Repository\ModListRepository;
use Symfony\Component\Security\Core\Security;

class ModListByNameDataProvider implements ItemDataProviderInterface, RestrictedDataProviderInterface
{
    public const SUPPORTED_OPERATION_NAME = 'get_by_name';

    /** @var ModListRepository */
    protected $modListRepository;

    public function __construct(Security $security, ModListRepository $modListRepository)
    {
        $this->modListRepository = $modListRepository;
    }

    public function supports(string $resourceClass, string $operationName = null, array $context = []): bool
    {
        return ModList::class === $resourceClass && self::SUPPORTED_OPERATION_NAME === $operationName;
    }

    public function getItem(string $resourceClass, $name, string $operationName = null, array $context = [])
    {
        return $this->modListRepository->findOneBy([
            'name' => $name,
        ]);
    }
}
