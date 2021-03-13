<?php

declare(strict_types=1);

namespace App\Repository\UserGroup;

use App\Entity\UserGroup\UserGroup;
use App\Entity\UserGroup\UserGroupInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserGroupInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserGroupInterface findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroupInterface[]    findAll()
 * @method UserGroupInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }
}
