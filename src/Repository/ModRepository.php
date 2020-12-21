<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mod\AbstractMod;
use App\Entity\Mod\ModInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
}
