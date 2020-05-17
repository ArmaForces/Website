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
            Events::preUpdate,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();

        if ($entity instanceof EntityInterface) {
            $entity->setLastUpdatedAt(new \DateTimeImmutable());
        }
    }
}
