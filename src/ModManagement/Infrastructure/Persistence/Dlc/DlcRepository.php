<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\Persistence\Dlc;

use App\ModManagement\Domain\Model\Dlc\Dlc;
use App\ModManagement\Domain\Model\Dlc\DlcInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|DlcInterface find($id, $lockMode = null, $lockVersion = null)
 * @method null|DlcInterface findOneBy(array $criteria, array $orderBy = null)
 * @method DlcInterface[]    findAll()
 * @method DlcInterface[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DlcRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dlc::class);
    }
}
