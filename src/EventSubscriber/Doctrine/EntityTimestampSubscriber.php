<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\EntityInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;

class EntityTimestampSubscriber implements EventSubscriber
{
    public function getSubscribedEvents(): array
    {
        return [
            Events::prePersist,
            Events::preUpdate,
        ];
    }

    public function prePersist(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof EntityInterface) {
            $this->updateTimestamps($entity);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof EntityInterface) {
            $this->updateTimestamps($entity);
        }
    }

    public function updateTimestamps(EntityInterface $entity): EntityInterface
    {
        if (!$entity->getCreatedAt()) {
            $entity->setCreatedAt(new \DateTimeImmutable());
        }

        $entity->setLastUpdatedAt(new \DateTimeImmutable());

        return $entity;
    }
}
