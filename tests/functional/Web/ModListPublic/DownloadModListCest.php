<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\ModListPublic;

use App\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\DataFixtures\Dlc\GlobalMobilizationDlcFixture;
use App\DataFixtures\Dlc\SogPrairieFireDlcFixture;
use App\DataFixtures\Dlc\Spearhead1944DlcFixture;
use App\DataFixtures\Mod\SteamWorkshop\Optional\AceInteractionMenuExpansionModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\Broken\ArmaForcesAceMedicalModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsCoreModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsMapsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupUnitsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupVehiclesModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupWeaponsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\Deprecated\LegacyArmaForcesModsModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\RhsAfrfModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\RhsGrefModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\RhsUsafModFixture;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\DataFixtures\ModList\RhsModListFixture;
use App\DataFixtures\User\User1Fixture;
use App\Entity\Dlc\Dlc;
use App\Entity\Mod\SteamWorkshopMod;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class DownloadModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function downloadModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $optionalMods = [
            AceInteractionMenuExpansionModFixture::ID,
            'invalid',
        ];

        $I->amOnPage(sprintf('/mod-list/%s/download/%s', DefaultModListFixture::NAME, json_encode($optionalMods)));

        $I->seeResponseContainsModListPresetWithMods('ArmaForces Default 2020_01_01 00_00.html', [
            $I->grabEntityFromRepository(Dlc::class, ['id' => CslaIronCurtainDlcFixture::ID]),
            $I->grabEntityFromRepository(Dlc::class, ['id' => GlobalMobilizationDlcFixture::ID]),
            $I->grabEntityFromRepository(Dlc::class, ['id' => SogPrairieFireDlcFixture::ID]),
            $I->grabEntityFromRepository(Dlc::class, ['id' => Spearhead1944DlcFixture::ID]),
        ], [
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => ArmaForcesAceMedicalModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => ArmaForcesMedicalModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupTerrainsCoreModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupTerrainsMapsModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupUnitsModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupVehiclesModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupWeaponsModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => RhsAfrfModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => RhsGrefModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => RhsUsafModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => LegacyArmaForcesModsModFixture::ID]),
        ]);
    }

    public function downloadModListAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $optionalMods = [
            AceInteractionMenuExpansionModFixture::ID,
            'invalid',
        ];

        $I->amOnPage(sprintf('/mod-list/%s/download/%s', DefaultModListFixture::NAME, json_encode($optionalMods)));

        $I->seeResponseContainsModListPresetWithMods('ArmaForces Default 2020_01_01 00_00.html', [
            $I->grabEntityFromRepository(Dlc::class, ['id' => CslaIronCurtainDlcFixture::ID]),
            $I->grabEntityFromRepository(Dlc::class, ['id' => GlobalMobilizationDlcFixture::ID]),
            $I->grabEntityFromRepository(Dlc::class, ['id' => SogPrairieFireDlcFixture::ID]),
            $I->grabEntityFromRepository(Dlc::class, ['id' => Spearhead1944DlcFixture::ID]),
        ], [
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => ArmaForcesAceMedicalModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => ArmaForcesMedicalModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupTerrainsCoreModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupTerrainsMapsModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupUnitsModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupVehiclesModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => CupWeaponsModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => RhsAfrfModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => RhsGrefModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => RhsUsafModFixture::ID]),
            $I->grabEntityFromRepository(SteamWorkshopMod::class, ['id' => LegacyArmaForcesModsModFixture::ID]),
        ]);
    }

    public function downloadModListAsAuthenticatedUserWhenModListInactive(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $optionalMods = [
            AceInteractionMenuExpansionModFixture::ID,
            'invalid',
        ];

        $I->amOnPage(sprintf('/mod-list/%s/download/%s', RhsModListFixture::NAME, json_encode($optionalMods)));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
