<?php

declare(strict_types=1);

namespace App\DataFixtures\ModList;

use App\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\DataFixtures\Dlc\GlobalMobilizationDlcFixture;
use App\DataFixtures\Dlc\SogPrairieFireDlcFixture;
use App\DataFixtures\Dlc\Spearhead1944DlcFixture;
use App\DataFixtures\Mod\Directory\ArmaScriptProfilerModFixture;
use App\DataFixtures\Mod\Directory\Deprecated\R3ModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Optional\AceInteractionMenuExpansionModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\Broken\ArmaForcesAceMedicalModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsCoreModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsMapsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupUnitsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupVehiclesModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupWeaponsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\Deprecated\LegacyArmaForcesModsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\Disabled\ArmaForcesJbadBuildingFixModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\RhsAfrfModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\RhsGrefModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\RhsUsafModFixture;
use App\DataFixtures\ModGroup\CupModGroupFixture;
use App\DataFixtures\ModGroup\RhsModGroupFixture;
use App\Entity\ModList\ModList;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class DefaultModListFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = 'f3e04dae-18a8-4533-99ea-d6d763ebabcf';
    public const NAME = 'Default';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
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

                    $this->getReference(CupTerrainsCoreModFixture::ID),
                    $this->getReference(CupTerrainsMapsModFixture::ID),
                    $this->getReference(CupUnitsModFixture::ID),
                    $this->getReference(CupVehiclesModFixture::ID),
                    $this->getReference(CupWeaponsModFixture::ID),

                    $this->getReference(RhsAfrfModFixture::ID),
                    $this->getReference(RhsGrefModFixture::ID),
                    $this->getReference(RhsUsafModFixture::ID),
                ],
                [
                    $this->getReference(CupModGroupFixture::ID),
                    $this->getReference(RhsModGroupFixture::ID),
                ],
                [
                    $this->getReference(CslaIronCurtainDlcFixture::ID),
                    $this->getReference(GlobalMobilizationDlcFixture::ID),
                    $this->getReference(SogPrairieFireDlcFixture::ID),
                    $this->getReference(Spearhead1944DlcFixture::ID),
                ],
                null,
                true,
                true,
            );

            $manager->persist($modList);
            $manager->flush();

            $this->addReference(self::ID, $modList);
        });
    }

    public function getDependencies(): array
    {
        return [
            R3ModFixture::class,
            ArmaScriptProfilerModFixture::class,

            AceInteractionMenuExpansionModFixture::class,
            ArmaForcesAceMedicalModFixture::class,
            LegacyArmaForcesModsModFixture::class,
            ArmaForcesJbadBuildingFixModFixture::class,
            ArmaForcesMedicalModFixture::class,

            CupTerrainsCoreModFixture::class,
            CupTerrainsMapsModFixture::class,
            CupUnitsModFixture::class,
            CupVehiclesModFixture::class,
            CupWeaponsModFixture::class,

            CupModGroupFixture::class,

            CslaIronCurtainDlcFixture::class,
            GlobalMobilizationDlcFixture::class,
            SogPrairieFireDlcFixture::class,
            Spearhead1944DlcFixture::class,
        ];
    }
}
