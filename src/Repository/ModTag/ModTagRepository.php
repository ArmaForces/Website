<?php

declare(strict_types=1);

namespace App\Repository\ModTag;

use App\Entity\ModTag\ModTag;
use App\Entity\ModTag\ModTagInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|ModTagInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModTagInterface findOneBy(array $criteria, array $orderBy = null)
 * @method ModTagInterface[]    findAll()
 * @method ModTagInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModTag::class);
    }
}
