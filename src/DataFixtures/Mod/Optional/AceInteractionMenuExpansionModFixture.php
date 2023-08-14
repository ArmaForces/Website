<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Optional;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AceInteractionMenuExpansionModFixture extends Fixture
{
    public const ID = '37f58e30-5194-4594-89af-4a82c7fc02be';

    public function load(ObjectManager $manager): void
    {
        $mod = new SteamWorkshopMod(
            Uuid::fromString(self::ID),
            'ACE Interaction Menu Expansion',
            null,
            null,
            ModTypeEnum::get(ModTypeEnum::OPTIONAL),
            1376867375
        );
        $mod->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 00:00:00'));

        $manager->persist($mod);
        $manager->flush();

        $this->addReference(self::ID, $mod);
    }
}
