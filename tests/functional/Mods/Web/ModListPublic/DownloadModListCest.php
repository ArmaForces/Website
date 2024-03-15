<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModListPublic;

use App\Mods\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\Mods\DataFixtures\Dlc\GlobalMobilizationDlcFixture;
use App\Mods\DataFixtures\Dlc\SogPrairieFireDlcFixture;
use App\Mods\DataFixtures\Dlc\Spearhead1944DlcFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Optional\AceInteractionMenuExpansionModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Broken\ArmaForcesAceMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsCoreModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsMapsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupUnitsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupVehiclesModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupWeaponsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Deprecated\LegacyArmaForcesModsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsAfrfModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsGrefModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsUsafModFixture;
use App\Mods\DataFixtures\ModList\DefaultModListFixture;
use App\Mods\DataFixtures\ModList\RhsModListFixture;
use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
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
