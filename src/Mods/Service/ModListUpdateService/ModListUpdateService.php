<?php

declare(strict_types=1);

namespace App\Mods\Service\ModListUpdateService;

use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\Entity\ModList\ModList;
use App\Users\Entity\User\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\SecurityBundle\Security;

class ModListUpdateService implements ModListUpdateServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function updateModListsAssociatedWithModGroup(ModGroup $modGroup): void
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof User) {
            return;
        }

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->update(ModList::class, 'ml')

            ->set('ml.lastUpdatedAt', ':dateTime')
            ->setParameter('dateTime', new \DateTimeImmutable())

            ->set('ml.lastUpdatedBy', ':user')
            ->setParameter('user', $currentUser)

            ->andWhere($expr->isMemberOf(':modGroupId', 'ml.modGroups'))
            ->setParameter('modGroupId', $modGroup->getId()->toString())
        ;

        $queryBuilder->getQuery()->execute();
    }
}
