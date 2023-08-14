<?php

declare(strict_types=1);

namespace App\Repository\Mod;

use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\ModGroup\ModGroup;
use App\Entity\ModList\ModList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AbstractMod find($id, $lockMode = null, $lockVersion = null)
 * @method null|AbstractMod findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractMod[]    findAll()
 * @method AbstractMod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractMod::class);
    }

    /**
     * @return AbstractMod[]
     */
    public function findIncludedMods(ModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();

        $qb
            ->addSelect('m')
            ->from(AbstractMod::class, 'm')
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return SteamWorkshopMod[]
     */
    public function findIncludedSteamWorkshopMods(ModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->andWhere($expr->in('m.type', [
                ModTypeEnum::CLIENT_SIDE,
                ModTypeEnum::OPTIONAL,
                ModTypeEnum::REQUIRED,
            ]))
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return SteamWorkshopMod[]
     */
    public function findIncludedOptionalSteamWorkshopMods(ModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->andWhere($expr->in('m.type', [
                ModTypeEnum::CLIENT_SIDE,
                ModTypeEnum::OPTIONAL,
            ]))
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return SteamWorkshopMod[]
     */
    public function findIncludedRequiredSteamWorkshopMods(ModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->andWhere($expr->eq('m.type', $expr->literal(ModTypeEnum::REQUIRED)))
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    protected function applyIncludedModsFilter(QueryBuilder $queryBuilder, ModList $modList): void
    {
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->leftJoin(ModList::class, 'ml', Join::WITH, (string) $expr->isMemberOf('m', 'ml.mods'))
            ->leftJoin(ModGroup::class, 'mg', Join::WITH, (string) $expr->isMemberOf('m', 'mg.mods'))
            ->leftJoin(ModList::class, 'mgml', Join::WITH, (string) $expr->isMemberOf('mg', 'mgml.modGroups'))
            ->andWhere($expr->orX(
                $expr->eq('ml.id', $expr->literal($modList->getId()->toString())),
                $expr->eq('mgml.id', $expr->literal($modList->getId()->toString()))
            ))
        ;
    }
}
