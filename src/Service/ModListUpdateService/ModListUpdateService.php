<?php

declare(strict_types=1);

namespace App\Service\ModListUpdateService;

use App\Entity\ModGroup\ModGroupInterface;
use App\Entity\ModList\ModList;
use App\Entity\User\UserInterface;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Security\Core\Security;

class ModListUpdateService implements ModListUpdateServiceInterface
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private Security $security,
    ) {
    }

    public function updateModListsAssociatedWithModGroup(ModGroupInterface $modGroup): void
    {
        $currentUser = $this->security->getUser();
        if (!$currentUser instanceof UserInterface) {
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
