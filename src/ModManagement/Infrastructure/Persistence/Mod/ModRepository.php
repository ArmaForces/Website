<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Persistence\Mod;

use App\ModManagement\Domain\Model\Mod\AbstractMod;
use App\ModManagement\Domain\Model\Mod\Enum\ModTypeEnum;
use App\ModManagement\Domain\Model\Mod\ModInterface;
use App\ModManagement\Domain\Model\Mod\SteamWorkshopMod;
use App\ModManagement\Domain\Model\Mod\SteamWorkshopModInterface;
use App\ModManagement\Domain\Model\ModGroup\ModGroup;
use App\ModManagement\Domain\Model\ModList\ModList;
use App\ModManagement\Domain\Model\ModList\ModListInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr\Join;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|ModInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModInterface findOneBy(array $criteria, array $orderBy = null)
 * @method ModInterface[]    findAll()
 * @method ModInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractMod::class);
    }

    /**
     * @return ModInterface[]
     */
    public function findIncludedMods(ModListInterface $modList): array
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
     * @return SteamWorkshopModInterface[]
     */
    public function findIncludedSteamWorkshopMods(ModListInterface $modList): array
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
     * @return SteamWorkshopModInterface[]
     */
    public function findIncludedOptionalSteamWorkshopMods(ModListInterface $modList): array
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
     * @return SteamWorkshopModInterface[]
     */
    public function findIncludedRequiredSteamWorkshopMods(ModListInterface $modList): array
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

    protected function applyIncludedModsFilter(QueryBuilder $queryBuilder, ModListInterface $modList): void
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
