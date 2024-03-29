<?php

declare(strict_types=1);

namespace App\Mods\Repository\Mod;

use App\Mods\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method null|SteamWorkshopMod find($id, $lockMode = null, $lockVersion = null)
 * @method null|SteamWorkshopMod findOneBy(array $criteria, array $orderBy = null)
 * @method SteamWorkshopMod[]    findAll()
 * @method SteamWorkshopMod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SteamWorkshopModRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, SteamWorkshopMod::class);
    }

    public function findOneByItemId(int $itemId): ?SteamWorkshopMod
    {
        return $this->findOneBy([
            'itemId' => $itemId,
        ]);
    }
}
