<?php

declare(strict_types=1);

namespace App\Tests\Unit\Mods\EventListener\Doctrine;

use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\EventListener\Doctrine\ModGroupUpdatedListener;
use App\Mods\Service\ModListUpdateService\ModListUpdateServiceInterface;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\UnitOfWork;

class ModGroupUpdatedListenerTest extends Unit
{
    public function testMarkAssociatedModListsAsUpdated(): void
    {
        $changeSet = [
            'name' => [
                'old name',
                'new name',
            ],
        ];

        $modGroup = $this->createMock(ModGroup::class);
        $entityManager = $this->createEntityManagerMock($changeSet);

        $modListUpdateService = $this->createMock(ModListUpdateServiceInterface::class);
        $modListUpdateService
            ->expects(self::once())
            ->method('updateModListsAssociatedWithModGroup')
            ->with($modGroup)
        ;

        $modGroupUpdatedListener = new ModGroupUpdatedListener($entityManager, $modListUpdateService);
        $modGroupUpdatedListener->preUpdate($modGroup);
    }

    public function testDoNotMarkAssociatedModListsAsUpdatedIfThereAreNoChangesToEntity(): void
    {
        $changeSet = [];

        $modGroup = $this->createMock(ModGroup::class);
        $entityManager = $this->createEntityManagerMock($changeSet);

        $modListUpdateService = $this->createMock(ModListUpdateServiceInterface::class);
        $modListUpdateService
            ->expects(self::never())
            ->method('updateModListsAssociatedWithModGroup')
            ->with($modGroup)
        ;

        $modGroupUpdatedListener = new ModGroupUpdatedListener($entityManager, $modListUpdateService);
        $modGroupUpdatedListener->preUpdate($modGroup);
    }

    private function createEntityManagerMock(array $changeSet): EntityManagerInterface
    {
        $unitOfWork = $this->createMock(UnitOfWork::class);
        $unitOfWork->method('getEntityChangeSet')->willReturn($changeSet);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entityManager->method('getUnitOfWork')->willReturn($unitOfWork);

        return $entityManager;
    }
}
