<?php

declare(strict_types=1);

namespace App\Mods\Repository\ModList;

use App\Mods\Entity\ModList\AbstractModList;
use App\Mods\Entity\ModList\StandardModList;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|StandardModList find($id, $lockMode = null, $lockVersion = null)
 * @method null|StandardModList findOneBy(array $criteria, array $orderBy = null)
 * @method StandardModList[]    findAll()
 * @method StandardModList[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModListRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AbstractModList::class);
    }

    public function findOneByName(string $name): ?AbstractModList
    {
        return $this->findOneBy(['name' => $name]);
    }
}
