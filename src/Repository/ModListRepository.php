<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ModList\ModList;
use App\Entity\ModList\ModListInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
}
