<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\DataFixtures\ModList;

use App\ModManagement\Domain\Model\ModList\ModList;
use App\ModManagement\Infrastructure\DataFixtures\Mod\Optional;
use App\ModManagement\Infrastructure\DataFixtures\Mod\Required;
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
            $this->getReference(Optional\AceInteractionMenuExpansionModFixture::ID),
            $this->getReference(Required\ArmaForcesModsModFixture::ID),
            $this->getReference(Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $this->getReference(Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this->getReference(Required\Disabled\ArmaForcesJbadBuildingFixModFixture::ID),
        ];

        foreach ($mods as $mod) {
            $modList->addMod($mod);
        }

        $manager->persist($modList);
        $manager->flush();

        $this->addReference(self::ID, $modList);
    }
}
