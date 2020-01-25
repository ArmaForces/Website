<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mod\Mod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|Mod find($id, $lockMode = null, $lockVersion = null)
 * @method null|Mod findOneBy(array $criteria, array $orderBy = null)
 * @method Mod[]    findAll()
 * @method Mod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mod::class);
    }
}
