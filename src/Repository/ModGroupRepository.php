<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ModGroup\ModGroup;
use App\Entity\ModGroup\ModGroupInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|ModGroupInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModGroupInterface findOneBy(array $criteria, array $orderBy = null)
 * @method ModGroupInterface[]    findAll()
 * @method ModGroupInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModGroup::class);
    }
}
