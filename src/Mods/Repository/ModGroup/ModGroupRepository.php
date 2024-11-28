<?php

declare(strict_types=1);

namespace App\Mods\Repository\ModGroup;

use App\Mods\Entity\ModGroup\ModGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|ModGroup find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModGroup findOneBy(array $criteria, array $orderBy = null)
 * @method ModGroup[]    findAll()
 * @method ModGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModGroup::class);
    }
}
