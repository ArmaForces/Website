<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\Mod\SteamWorkshop\Required;

use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Shared\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesMedicalModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '0e4e059c-eef6-42a9-aec3-abdab344ec21';
    public const ITEM_ID = 1981535406;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'ArmaForces - Medical',
                null,
                null,
                ModTypeEnum::REQUIRED,
                self::ITEM_ID
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
