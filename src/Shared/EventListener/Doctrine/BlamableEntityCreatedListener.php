<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Doctrine;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Users\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::prePersist, method: 'prePersist', entity: AbstractBlamableEntity::class)]
class BlamableEntityCreatedListener
{
    public function __construct(
        private Security $security
    ) {
    }

    public function prePersist(AbstractBlamableEntity $entity): void
    {
        $currentUser = $this->security->getUser();
        if ($currentUser instanceof User) {
            $entity->created($currentUser);
        }
    }
}
