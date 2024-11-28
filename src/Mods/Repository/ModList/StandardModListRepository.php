<?php

declare(strict_types=1);

namespace App\Mods\Repository\ModList;

use App\Mods\Entity\ModList\StandardModList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|StandardModList find($id, $lockMode = null, $lockVersion = null)
 * @method null|StandardModList findOneBy(array $criteria, array $orderBy = null)
 * @method StandardModList[]    findAll()
 * @method StandardModList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StandardModListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StandardModList::class);
    }

    public function findOneByName(string $name): ?StandardModList
    {
        return $this->findOneBy(['name' => $name]);
    }
}
