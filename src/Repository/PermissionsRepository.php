<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Permissions\Permissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|Permissions find($id, $lockMode = null, $lockVersion = null)
 * @method null|Permissions findOneBy(array $criteria, array $orderBy = null)
 * @method Permissions[]    findAll()
 * @method Permissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Permissions::class);
    }
}
