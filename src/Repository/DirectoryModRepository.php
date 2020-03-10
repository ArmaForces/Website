<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mod\DirectoryMod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|DirectoryMod find($id, $lockMode = null, $lockVersion = null)
 * @method null|DirectoryMod findOneBy(array $criteria, array $orderBy = null)
 * @method DirectoryMod[]    findAll()
 * @method DirectoryMod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DirectoryModRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DirectoryMod::class);
    }
}
