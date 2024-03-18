<?php

declare(strict_types=1);

namespace App\Mods\EventListener\Doctrine;

use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\Service\ModListUpdateService\ModListUpdateServiceInterface;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Events;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: ModGroup::class)]
class ModGroupUpdatedListener
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModListUpdateServiceInterface $modListUpdateService,
    ) {
    }

    public function preUpdate(ModGroup $modGroup): void
    {
        // Do nothing if updated entity is not a Mod Group or no changes were made to the entity
        if (!$this->entityManager->getUnitOfWork()->getEntityChangeSet($modGroup)) {
            return;
        }

        $this->modListUpdateService->updateModListsAssociatedWithModGroup($modGroup);
    }
}
