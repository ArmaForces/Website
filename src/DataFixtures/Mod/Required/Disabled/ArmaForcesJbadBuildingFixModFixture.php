<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Required\Disabled;

use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesJbadBuildingFixModFixture extends Fixture
{
    public const ID = 'b8e88103-69d2-438b-8d89-933ccfdb3a5a';

    public function load(ObjectManager $manager): void
    {
        $mod = new SteamWorkshopMod(
            Uuid::fromString(self::ID),
            '[OBSOLETE] ArmaForces - JBAD Building Fix',
            null,
            ModStatusEnum::get(ModStatusEnum::DISABLED),
            ModTypeEnum::get(ModTypeEnum::REQUIRED),
            1781106281
        );

        $manager->persist($mod);
        $manager->flush();

        $this->addReference(self::ID, $mod);
    }
}
