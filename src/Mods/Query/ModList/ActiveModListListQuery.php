<?php

declare(strict_types=1);

namespace App\Mods\Query\ModList;

use App\Mods\Entity\ModList\AbstractModList;
use App\Mods\Entity\ModList\StandardModList;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Query\Expr\Join;

class ActiveModListListQuery
{
    public function __construct(
        private EntityManagerInterface $entityManager
    ) {
    }

    /**
     * @return AbstractModList[]
     */
    public function getResult(): array
    {
        $qb = $this->entityManager->createQueryBuilder();
        $expr = $qb->expr();

        $qb
            ->addSelect('ml')
            ->from(AbstractModList::class, 'ml')
            ->leftJoin(StandardModList::class, 'sml', Join::WITH, $expr->eq('ml.id', 'sml.id'))
            ->andWhere($expr->eq('ml.active', 'true'))
            ->addOrderBy('CASE WHEN sml.approved = true THEN true ELSE false END', 'DESC')
            ->addOrderBy('ml.name', 'ASC')
        ;

        return $qb->getQuery()->getResult();
    }
}
