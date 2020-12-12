<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\Mod\SteamWorkshopModInterface;
use App\Entity\ModGroup\ModGroup;
use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method null|ModListInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModListInterface findOneBy(array $criteria, array $orderBy = null)
 * @method ModListInterface[]    findAll()
 * @method ModListInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModList::class);
    }

    /**
     * @return SteamWorkshopModInterface[]
     */
    public function findIncludedMods(ModListInterface $modList): array
    {
        $qb = $this->getEntityManager()->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('m')
            ->from(SteamWorkshopMod::class, 'm')
            ->leftJoin(ModGroup::class, 'mg', Join::WITH, (string) $expr->isMemberOf('m', 'mg.mods'))
            ->leftJoin(ModList::class, 'ml', Join::WITH, (string) $expr->isMemberOf('m', 'ml.mods'))
            ->andWhere(
                $expr->orX(
                    $expr->eq('ml.id', $expr->literal($modList->getId())),
                    $expr->isMemberOf('m', 'mg.mods')
                )
            )
            ->addOrderBy('m.name', 'ASC')
        ;

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
            ->leftJoin(ModGroup::class, 'mg', Join::WITH, (string) $expr->isMemberOf('m', 'mg.mods'))
            ->leftJoin(ModList::class, 'ml', Join::WITH, (string) $expr->isMemberOf('m', 'ml.mods'))
            ->andWhere($expr->in('m.type', [
                ModTypeEnum::CLIENT_SIDE,
                ModTypeEnum::OPTIONAL,
                ModTypeEnum::REQUIRED,
            ]))
            ->andWhere(
                $expr->orX(
                    $expr->eq('ml.id', $expr->literal($modList->getId())),
                    $expr->isMemberOf('m', 'mg.mods')
                )
            )
            ->addOrderBy('m.name', 'ASC')
        ;

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
            ->leftJoin(ModGroup::class, 'mg', Join::WITH, (string) $expr->isMemberOf('m', 'mg.mods'))
            ->leftJoin(ModList::class, 'ml', Join::WITH, (string) $expr->isMemberOf('m', 'ml.mods'))
            ->andWhere($expr->in('m.type', [
                ModTypeEnum::CLIENT_SIDE,
                ModTypeEnum::OPTIONAL,
            ]))
            ->andWhere(
                $expr->orX(
                    $expr->eq('ml.id', $expr->literal($modList->getId())),
                    $expr->isMemberOf('m', 'mg.mods')
                )
            )
            ->addOrderBy('m.name', 'ASC')
        ;

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
            ->leftJoin(ModGroup::class, 'mg', Join::WITH, (string) $expr->isMemberOf('m', 'mg.mods'))
            ->leftJoin(ModList::class, 'ml', Join::WITH, (string) $expr->isMemberOf('m', 'ml.mods'))
            ->andWhere($expr->eq('m.type', $expr->literal(ModTypeEnum::REQUIRED)))
            ->andWhere(
                $expr->orX(
                    $expr->eq('ml.id', $expr->literal($modList->getId())),
                    $expr->isMemberOf('m', 'mg.mods')
                )
            )
            ->addOrderBy('m.name', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }
}
