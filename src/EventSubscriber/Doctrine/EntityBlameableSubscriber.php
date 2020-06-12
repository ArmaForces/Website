<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\EntityInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

class EntityBlameableSubscriber implements EventSubscriber
{
    /** @var Security */
    protected $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

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
        $currentUser = $this->security->getUser();

        if ($entity instanceof EntityInterface && $currentUser) {
            $entity->setCreatedBy($currentUser);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entity = $args->getEntity();
        $currentUser = $this->security->getUser();

        if ($entity instanceof EntityInterface && $currentUser) {
            $entity->setLastUpdatedBy($currentUser);
        }
    }
}
