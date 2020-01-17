<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User\UserEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method null|UserEntity find($id, $lockMode = null, $lockVersion = null)
 * @method null|UserEntity findOneBy(array $criteria, array $orderBy = null)
 * @method UserEntity[]    findAll()
 * @method UserEntity[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserEntityRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserEntity::class);
    }
}
