<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Required;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesModsModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '0e4e059c-eef6-42a9-aec3-abdab344ec21';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'ArmaForces - Mods',
                null,
                null,
                ModTypeEnum::REQUIRED,
                1934142795
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
