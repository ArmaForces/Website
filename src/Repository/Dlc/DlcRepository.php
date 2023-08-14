<?php

declare(strict_types=1);

namespace App\Repository\Dlc;

use App\Entity\Dlc\Dlc;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|Dlc find($id, $lockMode = null, $lockVersion = null)
 * @method null|Dlc findOneBy(array $criteria, array $orderBy = null)
 * @method Dlc[]    findAll()
 * @method Dlc[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DlcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dlc::class);
    }
}
