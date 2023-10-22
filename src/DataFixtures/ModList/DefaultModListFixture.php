<?php

declare(strict_types=1);

namespace App\DataFixtures\ModList;

use App\DataFixtures\Mod\Optional;
use App\DataFixtures\Mod\Required;
use App\Entity\ModList\ModList;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class DefaultModListFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'f3e04dae-18a8-4533-99ea-d6d763ebabcf';

    public function load(ObjectManager $manager): void
    {
        $mods = [
            $this->getReference(Optional\AceInteractionMenuExpansionModFixture::ID),
            $this->getReference(Required\ArmaForcesModsModFixture::ID),
            $this->getReference(Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $this->getReference(Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this->getReference(Required\Disabled\ArmaForcesJbadBuildingFixModFixture::ID),
        ];

        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager, $mods): void {
            $modList = new ModList(
                Uuid::fromString(self::ID),
                'Default',
                null,
                $mods,
                [],
                [],
                null,
                true,
                false,
            );

            $manager->persist($modList);
            $manager->flush();

            $this->addReference(self::ID, $modList);
        });
    }
}
