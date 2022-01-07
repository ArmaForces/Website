<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\EventSubscriber\Doctrine;

use App\Entity\User\UserInterface;
use App\SharedKernel\Domain\Model\BlamableEntityInterface;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

class EntityBlamableSubscriber implements EventSubscriber
{
    public function __construct(
        private Security $security
    ) {
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
        $entity = $args->getObject();

        if ($currentUser instanceof UserInterface && $entity instanceof BlamableEntityInterface) {
            $entity->setCreatedBy($currentUser);
        }
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $currentUser = $this->security->getUser();
        $entity = $args->getObject();

        if ($currentUser instanceof UserInterface && $entity instanceof BlamableEntityInterface) {
            $entity->setLastUpdatedAt(new \DateTimeImmutable());
            $entity->setLastUpdatedBy($currentUser);
        }
    }
}
