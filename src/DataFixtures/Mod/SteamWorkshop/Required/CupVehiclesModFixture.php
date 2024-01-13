<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\SteamWorkshop\Required;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CupVehiclesModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '7275d787-d3c2-43b3-a2fa-cc881b1b052f';
    public const ITEM_ID = 541888371;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'CUP Vehicles',
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
