<?php

declare(strict_types=1);

namespace App\Mods\Repository\ModList;

use App\Mods\Entity\ModList\ExternalModList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|ExternalModList find($id, $lockMode = null, $lockVersion = null)
 * @method null|ExternalModList findOneBy(array $criteria, array $orderBy = null)
 * @method ExternalModList[]    findAll()
 * @method ExternalModList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ExternalModListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ExternalModList::class);
    }
}
