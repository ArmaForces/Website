<?php

declare(strict_types=1);

namespace App\Shared\EventListener\Doctrine;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Users\Entity\User\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsEntityListener;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

#[AsEntityListener(event: Events::preUpdate, method: 'preUpdate', entity: AbstractBlamableEntity::class)]
class BlamableEntityUpdatedListener
{
    public function __construct(
        private Security $security
    ) {
    }

    public function preUpdate(AbstractBlamableEntity $entity): void
    {
        $currentUser = $this->security->getUser();
        if ($currentUser instanceof User) {
            $entity->updated($currentUser);
        }
    }
}
