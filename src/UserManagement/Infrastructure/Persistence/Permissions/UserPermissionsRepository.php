<?php

declare(strict_types=1);

namespace App\UserManagement\Infrastructure\Persistence\Permissions;

use App\UserManagement\Domain\Model\Permissions\PermissionsInterface;
use App\UserManagement\Domain\Model\Permissions\UserPermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|PermissionsInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|PermissionsInterface findOneBy(array $criteria, array $orderBy = null)
 * @method PermissionsInterface[]    findAll()
 * @method PermissionsInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPermissions::class);
    }
}
