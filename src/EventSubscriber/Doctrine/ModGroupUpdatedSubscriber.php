<?php

declare(strict_types=1);

namespace App\EventSubscriber\Doctrine;

use App\Entity\ModGroup\ModGroupInterface;
use App\Entity\ModList\ModList;
use App\Entity\User\User;
use App\Repository\ModList\ModListRepository;
use Doctrine\Common\EventSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Event\PostFlushEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Security\Core\Security;

class ModGroupUpdatedSubscriber implements EventSubscriber
{
    protected Security $security;
    protected ?ModGroupInterface $updatedModGroup = null;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function getSubscribedEvents(): array
    {
        return [
            Events::preUpdate,
            Events::postFlush,
        ];
    }

    public function preUpdate(LifecycleEventArgs $args): void
    {
        $entityManager = $args->getEntityManager();
        $modGroup = $args->getEntity();

        // Do nothing if updated entity is not a Mod Group or no changes were made to the entity
        if (!$modGroup instanceof ModGroupInterface || !$entityManager->getUnitOfWork()->getEntityChangeSet($modGroup)) {
            return;
        }

        // Save Mod Group for update of associated Mod Lists
        $this->updatedModGroup = $modGroup;
    }

    public function postFlush(PostFlushEventArgs $args): void
    {
        // Do nothing if no Mod Group updated
        if (!$this->updatedModGroup) {
            return;
        }

        $entityManager = $args->getEntityManager();
        /** @var ModListRepository $modListRepository */
        $modListRepository = $entityManager->getRepository(ModList::class);
        /** @var null|User $currentUser */
        $currentUser = $this->security->getUser();

        // Get Mod Lists that use this Mod Group and update their "last changed by/at" properties
        $modLists = $modListRepository->findModListsContainingModGroup($this->updatedModGroup);
        foreach ($modLists as $modList) {
            $modList->setLastUpdatedAt(new \DateTimeImmutable());
            $modList->setLastUpdatedBy($currentUser);
        }

        // Clear updated Mod Group. This prevents executing this method after next flush
        $this->updatedModGroup = null;

        $entityManager->flush();
    }
}
