<?php

declare(strict_types=1);

namespace App\Users\Repository\Permissions;

use App\Users\Entity\Permissions\AbstractPermissions;
use App\Users\Entity\Permissions\UserPermissions;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|AbstractPermissions find($id, $lockMode = null, $lockVersion = null)
 * @method null|AbstractPermissions findOneBy(array $criteria, array $orderBy = null)
 * @method AbstractPermissions[]    findAll()
 * @method AbstractPermissions[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserPermissionsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserPermissions::class);
    }
}
