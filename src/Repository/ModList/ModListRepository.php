<?php

declare(strict_types=1);

namespace App\Repository\ModList;

use App\Entity\ModGroup\ModGroup;
use App\Entity\ModList\ModList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|ModList find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModList findOneBy(array $criteria, array $orderBy = null)
 * @method ModList[]    findAll()
 * @method ModList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModList::class);
    }

    /**
     * @return ModList[]
     */
    public function findModListsContainingModGroup(ModGroup $modGroup): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('ml')
            ->from(ModList::class, 'ml')
            ->join('ml.modGroups', 'mg')
            ->andWhere($expr->eq('mg.id', $expr->literal($modGroup->getId()->toString())))
        ;

        return $qb->getQuery()->getResult();
    }
}
