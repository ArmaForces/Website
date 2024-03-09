<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\Mod\SteamWorkshop\Required;

use App\Mods\Entity\Mod\Enum\ModTypeEnum;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Shared\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RhsUsafModFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'a37a6a74-3e82-4955-a836-0732d7816cc1';
    public const ITEM_ID = 843577117;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'RHS USAF',
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
