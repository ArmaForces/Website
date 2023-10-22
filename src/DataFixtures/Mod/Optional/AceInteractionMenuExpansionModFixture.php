<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Optional;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AceInteractionMenuExpansionModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '37f58e30-5194-4594-89af-4a82c7fc02be';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'ACE Interaction Menu Expansion',
                null,
                null,
                ModTypeEnum::OPTIONAL,
                1376867375
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
