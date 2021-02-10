<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Required;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesModsModFixture extends Fixture
{
    public const ID = '0e4e059c-eef6-42a9-aec3-abdab344ec21';

    public function load(ObjectManager $manager): void
    {
        $mod = new SteamWorkshopMod(
            Uuid::fromString(self::ID),
            'ArmaForces - Mods',
            ModTypeEnum::get(ModTypeEnum::REQUIRED),
            1934142795
        );
        $mod->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 00:00:00'));

        $manager->persist($mod);
        $manager->flush();

        $this->addReference(self::ID, $mod);
    }
}
