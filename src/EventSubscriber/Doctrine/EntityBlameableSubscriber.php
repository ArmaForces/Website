<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\EntityInterface;
use App\Entity\User\UserInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

class EntityBlameableSubscriber implements EventSubscriber
{
    protected Security $security;

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
        $currentUser = $this->security->getUser();
        $entity = $args->getEntity();

        if ($currentUser instanceof UserInterface && $entity instanceof EntityInterface) {
            $entity->setCreatedBy($currentUser);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $currentUser = $this->security->getUser();
        $entity = $args->getEntity();

        if ($currentUser instanceof UserInterface && $entity instanceof EntityInterface) {
            $entity->setLastUpdatedAt(new \DateTimeImmutable());
            $entity->setLastUpdatedBy($currentUser);
        }
    }
}
