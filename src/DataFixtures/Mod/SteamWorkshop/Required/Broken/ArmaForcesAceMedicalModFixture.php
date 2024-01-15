<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\SteamWorkshop\Required\Broken;

use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaForcesAceMedicalModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '2f1d2dea-a7a6-4509-b478-66a980d724ca';
    public const ITEM_ID = 1704054308;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new SteamWorkshopMod(
                Uuid::fromString(self::ID),
                'ArmaForces - ACE Medical [OBSOLETE]',
                null,
                ModStatusEnum::BROKEN,
                ModTypeEnum::REQUIRED,
                self::ITEM_ID
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
