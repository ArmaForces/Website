<?php

declare(strict_types=1);

namespace App\Shared\EventSubscriber\Doctrine;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Users\Entity\User\User;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\DependencyInjection\Attribute\AutoconfigureTag;

#[AutoconfigureTag('doctrine.event_subscriber')]
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

    public function prePersist(PrePersistEventArgs $args): void
    {
        $currentUser = $this->security->getUser();
        $entity = $args->getObject();

        if ($currentUser instanceof User && $entity instanceof AbstractBlamableEntity) {
            $entity->created($currentUser);
        }
    }

    public function preUpdate(PreUpdateEventArgs $args): void
    {
        $currentUser = $this->security->getUser();
        $entity = $args->getObject();

        if ($currentUser instanceof User && $entity instanceof AbstractBlamableEntity) {
            $entity->updated($currentUser);
        }
    }
}
