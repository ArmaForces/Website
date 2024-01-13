<?php

declare(strict_types=1);

namespace App\DataFixtures\ModGroup;

use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsCoreModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsMapsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupUnitsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupVehiclesModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupWeaponsModFixture;
use App\Entity\ModGroup\ModGroup;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CupModGroupFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = '2f183e71-30c1-41c5-a555-acdf5fcf559e';
    public const NAME = 'CUP';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $modGroup = new ModGroup(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                [
                    $this->getReference(CupTerrainsCoreModFixture::ID),
                    $this->getReference(CupTerrainsMapsModFixture::ID),
                    $this->getReference(CupUnitsModFixture::ID),
                    $this->getReference(CupVehiclesModFixture::ID),
                    $this->getReference(CupWeaponsModFixture::ID),
                ]
            );

            $manager->persist($modGroup);
            $manager->flush();

            $this->addReference(self::ID, $modGroup);
        });
    }

    public function getDependencies(): array
    {
        return [
            CupTerrainsCoreModFixture::class,
            CupTerrainsMapsModFixture::class,
            CupUnitsModFixture::class,
            CupVehiclesModFixture::class,
            CupWeaponsModFixture::class,
        ];
    }
}
