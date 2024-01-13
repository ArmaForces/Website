<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\SteamWorkshop\Required;

use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RhsGrefModFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'a34b691c-50a2-475e-bd0a-43e707c45db5';
    public const ITEM_ID = 843593391;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'RHS GREF',
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
