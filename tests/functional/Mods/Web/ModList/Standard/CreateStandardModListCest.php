<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\Standard;

use App\Mods\DataFixtures\Dlc\CslaIronCurtainDlcFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsAfrfModFixture;
use App\Mods\DataFixtures\ModGroup\CupModGroupFixture;
use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Mods\Entity\ModList\StandardModList;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryStub;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\DataFixtures\User\User2Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;
use Ramsey\Uuid\Uuid;

class CreateStandardModListCest
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

    public function createStandardModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createStandardModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createStandardModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCreate = true;
        });

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', '');
        $I->seeInField('Mod list description', '');
        $I->dontSee('Mod list owner');
        $I->seeCheckboxIsChecked('Mod list active');
        $I->dontSee('Mod list approved');
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_modGroups_'); // All mod group checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_dlcs_'); // All DLC checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_mods_'); // All mod checkboxes are unchecked

        // Fill form
        $I->fillField('Mod list name', 'Custom');
        $I->fillField('Mod list description', 'Custom modlist');
        $I->uncheckOption('Mod list active');
        $I->checkTableRowCheckbox(CupModGroupFixture::ID); // Mod group
        $I->checkTableRowCheckbox(CslaIronCurtainDlcFixture::ID); // DLC
        $I->checkTableRowCheckbox(RhsAfrfModFixture::ID); // Mod
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var StandardModList $modList */
        $modList = $I->grabEntityFromRepository(StandardModList::class, ['name' => 'Custom']);
        $I->assertSame('805c9fcd-d674-4a27-8f0c-78dbf2484bb2', $modList->getId()->toString());
        $I->assertSame('Custom', $modList->getName());
        $I->assertSame('Custom modlist', $modList->getDescription());
        $I->assertSame(User1Fixture::ID, $modList->getOwner()->getId()->toString()); // Current user
        $I->assertSame(false, $modList->isActive());
        $I->assertSame(false, $modList->isApproved());
        $I->assertSame([
            CupModGroupFixture::ID,
        ], array_map(fn (ModGroup $modGroup) => $modGroup->getId()->toString(), $modList->getModGroups()));
        $I->assertSame([
            CslaIronCurtainDlcFixture::ID,
        ], array_map(fn (Dlc $dlc) => $dlc->getId()->toString(), $modList->getDlcs()));
        $I->assertSame([
            RhsAfrfModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modList->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt());
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function createStandardModListAsAuthorizedUserWithModListUpdatePermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCreate = true;
            $user->getPermissions()->standardModListUpdate = true;
        });

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', '');
        $I->seeInField('Mod list description', '');
        $I->seeOptionIsSelected('Mod list owner', User1Fixture::USERNAME);
        $I->seeCheckboxIsChecked('Mod list active');
        $I->dontSee('Mod list approved');
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_modGroups_'); // All mod group checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_dlcs_'); // All DLC checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_mods_'); // All mod checkboxes are unchecked

        // Fill form
        $I->fillField('Mod list name', 'Custom');
        $I->fillField('Mod list description', 'Custom modlist');
        $I->selectOption('Mod list owner', User2Fixture::USERNAME);
        $I->uncheckOption('Mod list active');
        $I->checkTableRowCheckbox(CupModGroupFixture::ID); // Mod group
        $I->checkTableRowCheckbox(CslaIronCurtainDlcFixture::ID); // DLC
        $I->checkTableRowCheckbox(RhsAfrfModFixture::ID); // Mod
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var StandardModList $modList */
        $modList = $I->grabEntityFromRepository(StandardModList::class, ['name' => 'Custom']);
        $I->assertSame('Custom', $modList->getName());
        $I->assertSame('Custom modlist', $modList->getDescription());
        $I->assertSame(User2Fixture::ID, $modList->getOwner()->getId()->toString()); // Current user
        $I->assertSame(false, $modList->isActive());
        $I->assertSame(false, $modList->isApproved());
        $I->assertSame([
            CupModGroupFixture::ID,
        ], array_map(fn (ModGroup $modGroup) => $modGroup->getId()->toString(), $modList->getModGroups()));
        $I->assertSame([
            CslaIronCurtainDlcFixture::ID,
        ], array_map(fn (Dlc $dlc) => $dlc->getId()->toString(), $modList->getDlcs()));
        $I->assertSame([
            RhsAfrfModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modList->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt());
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function createStandardModListAsAuthorizedUserWithModListApprovePermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCreate = true;
            $user->getPermissions()->standardModListApprove = true;
        });

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', '');
        $I->seeInField('Mod list description', '');
        $I->dontSee('Mod list owner');
        $I->seeCheckboxIsChecked('Mod list active');
        $I->dontSeeCheckboxIsChecked('Mod list approved');
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_modGroups_'); // All mod group checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_dlcs_'); // All DLC checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('standard_mod_list_form_mods_'); // All mod checkboxes are unchecked

        // Fill form
        $I->fillField('Mod list name', 'Custom');
        $I->fillField('Mod list description', 'Custom modlist');
        $I->uncheckOption('Mod list active');
        $I->checkOption('Mod list approved');
        $I->checkTableRowCheckbox(CupModGroupFixture::ID); // Mod group
        $I->checkTableRowCheckbox(CslaIronCurtainDlcFixture::ID); // DLC
        $I->checkTableRowCheckbox(RhsAfrfModFixture::ID); // Mod
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var StandardModList $modList */
        $modList = $I->grabEntityFromRepository(StandardModList::class, ['name' => 'Custom']);
        $I->assertSame('Custom', $modList->getName());
        $I->assertSame('Custom modlist', $modList->getDescription());
        $I->assertSame(User1Fixture::ID, $modList->getOwner()->getId()->toString()); // Current user
        $I->assertSame(false, $modList->isActive());
        $I->assertSame(true, $modList->isApproved());
        $I->assertSame([
            CupModGroupFixture::ID,
        ], array_map(fn (ModGroup $modGroup) => $modGroup->getId()->toString(), $modList->getModGroups()));
        $I->assertSame([
            CslaIronCurtainDlcFixture::ID,
        ], array_map(fn (Dlc $dlc) => $dlc->getId()->toString(), $modList->getDlcs()));
        $I->assertSame([
            RhsAfrfModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modList->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt());
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function createStandardModListAsAuthorizedUserWhenModListAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCreate = true;
        });

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', DefaultStandardModListFixture::NAME);
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'Mod list with the same name "Default" already exist.');
    }

    public function createStandardModListAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCreate = true;
        });

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', '');
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function createStandardModListAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListCreate = true;
        });

        $I->amOnPage('/standard-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', str_repeat('a', 256));
        $I->fillField('Mod list description', str_repeat('a', 256));
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }
}
