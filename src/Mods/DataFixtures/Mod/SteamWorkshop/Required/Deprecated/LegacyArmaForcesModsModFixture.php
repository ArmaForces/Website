<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Deprecated;

use App\Mods\Entity\Mod\Enum\ModStatusEnum;
use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Shared\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class LegacyArmaForcesModsModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '7e11c37e-930e-49e8-a87d-8f942d98edb0';
    public const ITEM_ID = 1639399387;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                '[legacy] ArmaForces - Mods',
                null,
                ModStatusEnum::DEPRECATED,
                ModTypeEnum::REQUIRED,
                self::ITEM_ID
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
