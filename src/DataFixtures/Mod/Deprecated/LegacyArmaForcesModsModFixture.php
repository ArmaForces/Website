<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Deprecated;

use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class LegacyArmaForcesModsModFixture extends Fixture
{
    public const ID = '7e11c37e-930e-49e8-a87d-8f942d98edb0';

    public function load(ObjectManager $manager): void
    {
        $mod = new SteamWorkshopMod(
            Uuid::fromString(self::ID),
            '[legacy] ArmaForces - Mods',
            ModTypeEnum::get(ModTypeEnum::REQUIRED),
            1639399387
        );
        $mod->setStatus(ModStatusEnum::get(ModStatusEnum::DEPRECATED));
        $mod->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 00:00:00'));

        $manager->persist($mod);
        $manager->flush();

        $this->addReference(self::ID, $mod);
    }
}
