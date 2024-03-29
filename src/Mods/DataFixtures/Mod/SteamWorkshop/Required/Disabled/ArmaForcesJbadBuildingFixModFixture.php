<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Disabled;

use App\Mods\Entity\Mod\Enum\ModStatusEnum;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Shared\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesJbadBuildingFixModFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'b8e88103-69d2-438b-8d89-933ccfdb3a5a';
    public const ITEM_ID = 1781106281;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                '[OBSOLETE] ArmaForces - JBAD Building Fix',
                null,
                ModStatusEnum::DISABLED,
                ModTypeEnum::REQUIRED,
                self::ITEM_ID
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
