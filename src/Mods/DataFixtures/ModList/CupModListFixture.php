<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\ModList;

use App\Mods\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\Mods\DataFixtures\Mod\Directory\ArmaScriptProfilerModFixture;
use App\Mods\DataFixtures\Mod\Directory\Deprecated\R3ModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Optional\AceInteractionMenuExpansionModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Broken\ArmaForcesAceMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Deprecated\LegacyArmaForcesModsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Disabled\ArmaForcesJbadBuildingFixModFixture;
use App\Mods\DataFixtures\ModGroup\CupModGroupFixture;
use App\Mods\Entity\ModList\ModList;
use App\Shared\Test\Traits\TimeTrait;
use App\Users\DataFixtures\User\User1Fixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CupModListFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = 'ea384489-c06c-4844-9e56-0e9a9c46bfaf';
    public const NAME = 'CUP';
    public const OWNER_ID = User1Fixture::ID;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $owner = $this->getReference(self::OWNER_ID);

            $modList = new ModList(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                [
                    $this->getReference(R3ModFixture::ID),
                    $this->getReference(ArmaScriptProfilerModFixture::ID),

                    $this->getReference(AceInteractionMenuExpansionModFixture::ID),
                    $this->getReference(ArmaForcesAceMedicalModFixture::ID),
                    $this->getReference(LegacyArmaForcesModsModFixture::ID),
                    $this->getReference(ArmaForcesJbadBuildingFixModFixture::ID),
                    $this->getReference(ArmaForcesMedicalModFixture::ID),
                ],
                [
                    $this->getReference(CupModGroupFixture::ID),
                ],
                [
                    $this->getReference(CslaIronCurtainDlcFixture::ID),
                ],
                $owner,
                true,
                false,
            );

            $modList->created($owner);

            $manager->persist($modList);
            $manager->flush();

            $this->addReference(self::ID, $modList);
        });
    }

    public function getDependencies(): array
    {
        return [
            User1Fixture::class, // owner

            R3ModFixture::class,
            ArmaScriptProfilerModFixture::class,

            AceInteractionMenuExpansionModFixture::class,
            ArmaForcesAceMedicalModFixture::class,
            LegacyArmaForcesModsModFixture::class,
            ArmaForcesJbadBuildingFixModFixture::class,
            ArmaForcesMedicalModFixture::class,

            CupModGroupFixture::class,

            CslaIronCurtainDlcFixture::class,
        ];
    }
}
