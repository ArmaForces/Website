<?php

declare(strict_types=1);

namespace App\DataFixtures\ModList;

use App\DataFixtures\Mod\ArmaForcesModsModFixture;
use App\DataFixtures\Mod\Broken;
use App\DataFixtures\Mod\Deprecated;
use App\DataFixtures\Mod\Disabled;
use App\Entity\ModList\ModList;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class DefaultModListFixture extends Fixture
{
    public const ID = 'f3e04dae-18a8-4533-99ea-d6d763ebabcf';

    public function load(ObjectManager $manager): void
    {
        $modList = new ModList(
            Uuid::fromString(self::ID),
            'Default'
        );
        $modList->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 00:00:00'));

        $mods = [
            $this->getReference(ArmaForcesModsModFixture::ID),

            $this->getReference(Broken\ArmaForcesAceMedicalModFixture::ID),
            $this->getReference(Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this->getReference(Disabled\ArmaForcesJbadBuildingFixModFixture::ID),
        ];

        foreach ($mods as $mod) {
            $modList->addMod($mod);
        }

        $manager->persist($modList);
        $manager->flush();

        $this->addReference(self::ID, $modList);
    }
}
