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
        /** @var null|UserInterface $currentUser */
        $currentUser = $this->security->getUser();
        $entity = $args->getEntity();

        if ($entity instanceof EntityInterface && $currentUser) {
            $entity->setCreatedBy($currentUser);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        /** @var null|UserInterface $currentUser */
        $currentUser = $this->security->getUser();
        $entity = $args->getEntity();

        if ($entity instanceof EntityInterface && $currentUser) {
            $entity->setLastUpdatedBy($currentUser);
        }
    }
}
