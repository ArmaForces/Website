<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

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
}
