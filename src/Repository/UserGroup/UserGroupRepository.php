<?php

declare(strict_types=1);

namespace App\Repository\UserGroup;

use App\Entity\UserGroup\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|UserGroup find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserGroup findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroup[]    findAll()
 * @method UserGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }
}
