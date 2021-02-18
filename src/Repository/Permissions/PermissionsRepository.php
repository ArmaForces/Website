<?php

declare(strict_types=1);

namespace App\Repository\Permissions;

use App\Entity\Permissions\AbstractPermissions;
use App\Entity\Permissions\PermissionsInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|PermissionsInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|PermissionsInterface findOneBy(array $criteria, array $orderBy = null)
 * @method PermissionsInterface[]    findAll()
 * @method PermissionsInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractPermissions::class);
    }
}
