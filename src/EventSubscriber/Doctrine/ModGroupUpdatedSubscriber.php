<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\ModGroup\ModGroupInterface;
use App\Service\ModListUpdateService\ModListUpdateServiceInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class ModGroupUpdatedSubscriber implements EventSubscriber
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private ModListUpdateServiceInterface $modListUpdateService,
    ) {
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $modGroup = $args->getObject();

        // Do nothing if updated entity is not a Mod Group or no changes were made to the entity
        if (!$modGroup instanceof ModGroupInterface || !$this->entityManager->getUnitOfWork()->getEntityChangeSet($modGroup)) {
            return;
        }

        $this->modListUpdateService->updateModListsAssociatedWithModGroup($modGroup);
    }
}
