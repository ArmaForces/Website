<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\Standard;

use App\Mods\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\Mods\DataFixtures\Dlc\SogPrairieFireDlcFixture;
use App\Mods\DataFixtures\Mod\Directory\ArmaScriptProfilerModFixture;
use App\Mods\DataFixtures\Mod\Directory\Deprecated\R3ModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Optional\AceInteractionMenuExpansionModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Broken\ArmaForcesAceMedicalModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Deprecated\LegacyArmaForcesModsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\Disabled\ArmaForcesJbadBuildingFixModFixture;
use App\Mods\DataFixtures\ModGroup\CupModGroupFixture;
use App\Mods\DataFixtures\ModGroup\RhsModGroupFixture;
use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\RhsStandardModListFixture;
use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\Entity\ModList\StandardModList;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryStub;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\DataFixtures\User\User3Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;
use Ramsey\Uuid\Uuid;

class CopyStandardModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2020-01-01T00:00:00+00:00');

        /** @var IdentifierFactoryStub $identifierFactory */
        $identifierFactory = $I->grabService(IdentifierFactoryStub::class);
        $identifierFactory->setIdentifiers([
            Uuid::fromString('805c9fcd-d674-4a27-8f0c-78dbf2484bb2'),
        ]);
    }

    public function copyStandardModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();
    }

    public function copyStandardModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function copyStandardModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', 'RHS');
        $I->seeInField('Mod list description', '');
        $I->dontSee('Mod list owner');
        $I->dontSeeCheckboxIsChecked('Mod list active');
        $I->dontSee('Mod list approved');
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_modGroups_', [
            RhsModGroupFixture::ID,
        ]); // Same as the source mod list
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_dlcs_', [
            CslaIronCurtainDlcFixture::ID,
        ]); // Same as the source mod list
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_mods_', [
            R3ModFixture::ID,
            ArmaScriptProfilerModFixture::ID,

            AceInteractionMenuExpansionModFixture::ID,
            ArmaForcesAceMedicalModFixture::ID,
            LegacyArmaForcesModsModFixture::ID,
            ArmaForcesJbadBuildingFixModFixture::ID,
            ArmaForcesMedicalModFixture::ID,
        ]); // Same as the source mod list

        // Fill form
        $I->fillField('Mod list name', 'Custom');
        $I->fillField('Mod list description', 'Custom modlist');
        $I->checkOption('Mod list active');
        $I->uncheckTableRowCheckbox(RhsModGroupFixture::ID); // Mod group
        $I->checkTableRowCheckbox(CupModGroupFixture::ID); // Mod group
        $I->uncheckTableRowCheckbox(CslaIronCurtainDlcFixture::ID); // DLC
        $I->checkTableRowCheckbox(SogPrairieFireDlcFixture::ID); // DLC
        $I->uncheckTableRowCheckbox(R3ModFixture::ID); // Mod
        $I->uncheckTableRowCheckbox(ArmaScriptProfilerModFixture::ID); // Mod
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var StandardModList $modList */
        $modList = $I->grabEntityFromRepository(StandardModList::class, ['name' => 'Custom']);
        $I->assertSame('805c9fcd-d674-4a27-8f0c-78dbf2484bb2', $modList->getId()->toString());
        $I->assertSame('Custom', $modList->getName());
        $I->assertSame('Custom modlist', $modList->getDescription());
        $I->assertSame(User1Fixture::ID, $modList->getOwner()->getId()->toString()); // Current user
        $I->assertSame(true, $modList->isActive());
        $I->assertSame(false, $modList->isApproved());
        $I->assertSame([
            CupModGroupFixture::ID,
        ], array_map(fn (ModGroup $modGroup) => $modGroup->getId()->toString(), $modList->getModGroups()));
        $I->assertSame([
            SogPrairieFireDlcFixture::ID,
        ], array_map(fn (Dlc $dlc) => $dlc->getId()->toString(), $modList->getDlcs()));
        $I->assertSame([
            AceInteractionMenuExpansionModFixture::ID,
            ArmaForcesAceMedicalModFixture::ID,
            LegacyArmaForcesModsModFixture::ID,
            ArmaForcesJbadBuildingFixModFixture::ID,
            ArmaForcesMedicalModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modList->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function copyStandardModListAsAuthorizedUserWithModListApprovePermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
            $user->getPermissions()->standardModListApprove = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', 'RHS');
        $I->seeInField('Mod list description', '');
        $I->dontSee('Mod list owner');
        $I->dontSeeCheckboxIsChecked('Mod list active');
        $I->dontSeeCheckboxIsChecked('Mod list approved');
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_modGroups_', [
            RhsModGroupFixture::ID,
        ]); // Same as the source mod list
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_dlcs_', [
            CslaIronCurtainDlcFixture::ID,
        ]); // Same as the source mod list
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_mods_', [
            R3ModFixture::ID,
            ArmaScriptProfilerModFixture::ID,

            AceInteractionMenuExpansionModFixture::ID,
            ArmaForcesAceMedicalModFixture::ID,
            LegacyArmaForcesModsModFixture::ID,
            ArmaForcesJbadBuildingFixModFixture::ID,
            ArmaForcesMedicalModFixture::ID,
        ]); // Same as the source mod list

        // Fill form
        $I->fillField('Mod list name', 'Custom');
        $I->fillField('Mod list description', 'Custom modlist');
        $I->checkOption('Mod list active');
        $I->checkOption('Mod list approved');
        $I->uncheckTableRowCheckbox(RhsModGroupFixture::ID); // Mod group
        $I->checkTableRowCheckbox(CupModGroupFixture::ID); // Mod group
        $I->uncheckTableRowCheckbox(CslaIronCurtainDlcFixture::ID); // DLC
        $I->checkTableRowCheckbox(SogPrairieFireDlcFixture::ID); // DLC
        $I->uncheckTableRowCheckbox(R3ModFixture::ID); // Mod
        $I->uncheckTableRowCheckbox(ArmaScriptProfilerModFixture::ID); // Mod
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var StandardModList $modList */
        $modList = $I->grabEntityFromRepository(StandardModList::class, ['name' => 'Custom']);
        $I->assertSame('Custom', $modList->getName());
        $I->assertSame('Custom modlist', $modList->getDescription());
        $I->assertSame(User1Fixture::ID, $modList->getOwner()->getId()->toString()); // Current user
        $I->assertSame(true, $modList->isActive());
        $I->assertSame(true, $modList->isApproved());
        $I->assertSame([
            CupModGroupFixture::ID,
        ], array_map(fn (ModGroup $modGroup) => $modGroup->getId()->toString(), $modList->getModGroups()));
        $I->assertSame([
            SogPrairieFireDlcFixture::ID,
        ], array_map(fn (Dlc $dlc) => $dlc->getId()->toString(), $modList->getDlcs()));
        $I->assertSame([
            AceInteractionMenuExpansionModFixture::ID,
            ArmaForcesAceMedicalModFixture::ID,
            LegacyArmaForcesModsModFixture::ID,
            ArmaForcesJbadBuildingFixModFixture::ID,
            ArmaForcesMedicalModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modList->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function copyStandardModListAsAuthorizedUserWithModListUpdatePermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
            $user->getPermissions()->standardModListUpdate = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', 'RHS');
        $I->seeInField('Mod list description', '');
        $I->seeOptionIsSelected('Mod list owner', User1Fixture::USERNAME); // Current user
        $I->dontSeeCheckboxIsChecked('Mod list active');
        $I->dontSee('Mod list approved');
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_modGroups_', [
            RhsModGroupFixture::ID,
        ]); // Same as the source mod list
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_dlcs_', [
            CslaIronCurtainDlcFixture::ID,
        ]); // Same as the source mod list
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_mods_', [
            R3ModFixture::ID,
            ArmaScriptProfilerModFixture::ID,

            AceInteractionMenuExpansionModFixture::ID,
            ArmaForcesAceMedicalModFixture::ID,
            LegacyArmaForcesModsModFixture::ID,
            ArmaForcesJbadBuildingFixModFixture::ID,
            ArmaForcesMedicalModFixture::ID,
        ]); // Same as the source mod list

        // Fill form
        $I->fillField('Mod list name', 'Custom');
        $I->fillField('Mod list description', 'Custom modlist');
        $I->selectOption('Mod list owner', User3Fixture::USERNAME);
        $I->checkOption('Mod list active');
        $I->uncheckTableRowCheckbox(RhsModGroupFixture::ID); // Mod group
        $I->checkTableRowCheckbox(CupModGroupFixture::ID); // Mod group
        $I->uncheckTableRowCheckbox(CslaIronCurtainDlcFixture::ID); // DLC
        $I->checkTableRowCheckbox(SogPrairieFireDlcFixture::ID); // DLC
        $I->uncheckTableRowCheckbox(R3ModFixture::ID); // Mod
        $I->uncheckTableRowCheckbox(ArmaScriptProfilerModFixture::ID); // Mod
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var StandardModList $modList */
        $modList = $I->grabEntityFromRepository(StandardModList::class, ['name' => 'Custom']);
        $I->assertSame('Custom', $modList->getName());
        $I->assertSame('Custom modlist', $modList->getDescription());
        $I->assertSame(User3Fixture::ID, $modList->getOwner()->getId()->toString());
        $I->assertSame(true, $modList->isActive());
        $I->assertSame(false, $modList->isApproved());
        $I->assertSame([
            CupModGroupFixture::ID,
        ], array_map(fn (ModGroup $modGroup) => $modGroup->getId()->toString(), $modList->getModGroups()));
        $I->assertSame([
            SogPrairieFireDlcFixture::ID,
        ], array_map(fn (Dlc $dlc) => $dlc->getId()->toString(), $modList->getDlcs()));
        $I->assertSame([
            AceInteractionMenuExpansionModFixture::ID,
            ArmaForcesAceMedicalModFixture::ID,
            LegacyArmaForcesModsModFixture::ID,
            ArmaForcesJbadBuildingFixModFixture::ID,
            ArmaForcesMedicalModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modList->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function copyStandardModListAsAuthorizedUserWhenModListAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', DefaultStandardModListFixture::NAME);
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'Mod list with the same name "Default" already exist.');
    }

    public function copyStandardModListAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', '');
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function copyStandardModListAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', str_repeat('a', 256));
        $I->fillField('Mod list description', str_repeat('a', 256));
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }

    public function copyStandardModListAsAuthorizedUserWhenModListDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCopy = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/copy', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
