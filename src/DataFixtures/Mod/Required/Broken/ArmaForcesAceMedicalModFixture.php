<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Required\Broken;

use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesAceMedicalModFixture extends Fixture
{
    public const ID = '2f1d2dea-a7a6-4509-b478-66a980d724ca';

    public function load(ObjectManager $manager): void
    {
        $mod = new SteamWorkshopMod(
            Uuid::fromString(self::ID),
            'ArmaForces - ACE Medical [OBSOLETE]',
            ModTypeEnum::get(ModTypeEnum::REQUIRED),
            1704054308
        );
        $mod->setStatus(ModStatusEnum::get(ModStatusEnum::BROKEN));
        $mod->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 00:00:00'));

        $manager->persist($mod);
        $manager->flush();

        $this->addReference(self::ID, $mod);
    }
}
