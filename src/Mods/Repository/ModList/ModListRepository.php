<?php

declare(strict_types=1);

namespace App\Mods\Repository\ModList;

use App\Mods\Entity\ModList\ModList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|ModList find($id, $lockMode = null, $lockVersion = null)
 * @method null|ModList findOneBy(array $criteria, array $orderBy = null)
 * @method ModList[]    findAll()
 * @method ModList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModList::class);
    }

    public function findOneByName(string $name): ?ModList
    {
        return $this->findOneBy(['name' => $name]);
    }
}
