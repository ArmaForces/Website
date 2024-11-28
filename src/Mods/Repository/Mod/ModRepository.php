<?php

declare(strict_types=1);

namespace App\Mods\Repository\Mod;

use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\Entity\ModList\StandardModList;
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
    public function findIncludedMods(StandardModList $modList): array
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
    public function findIncludedSteamWorkshopMods(StandardModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->andWhere($expr->in('m.type', [
                ModTypeEnum::CLIENT_SIDE->value,
                ModTypeEnum::OPTIONAL->value,
                ModTypeEnum::REQUIRED->value,
            ]))
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return SteamWorkshopMod[]
     */
    public function findIncludedOptionalSteamWorkshopMods(StandardModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->andWhere($expr->in('m.type', [
                ModTypeEnum::CLIENT_SIDE->value,
                ModTypeEnum::OPTIONAL->value,
            ]))
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    /**
     * @return SteamWorkshopMod[]
     */
    public function findIncludedRequiredSteamWorkshopMods(StandardModList $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->andWhere($expr->eq('m.type', $expr->literal(ModTypeEnum::REQUIRED->value)))
            ->addOrderBy('m.name', 'ASC')
        ;
        $this->applyIncludedModsFilter($qb, $modList);

        return $qb->getQuery()->getResult();
    }

    private function applyIncludedModsFilter(QueryBuilder $queryBuilder, StandardModList $modList): void
    {
        $expr = $queryBuilder->expr();

        $queryBuilder
            ->leftJoin(StandardModList::class, 'ml', Join::WITH, (string) $expr->isMemberOf('m', 'ml.mods'))
            ->leftJoin(ModGroup::class, 'mg', Join::WITH, (string) $expr->isMemberOf('m', 'mg.mods'))
            ->leftJoin(StandardModList::class, 'mgml', Join::WITH, (string) $expr->isMemberOf('mg', 'mgml.modGroups'))
            ->andWhere($expr->orX(
                $expr->eq('ml.id', $expr->literal($modList->getId()->toString())),
                $expr->eq('mgml.id', $expr->literal($modList->getId()->toString()))
            ))
        ;
    }
}
