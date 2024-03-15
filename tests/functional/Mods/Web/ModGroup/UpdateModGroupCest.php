<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModGroup;

use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsCoreModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsMapsModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsAfrfModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsGrefModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsUsafModFixture;
use App\Mods\DataFixtures\ModGroup\RhsModGroupFixture;
use App\Mods\Entity\Mod\AbstractMod;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class UpdateModGroupCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2021-01-01T00:00:00+00:00');
    }

    public function updateModGroupAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod-group/%s/update', RhsModGroupFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();
    }

    public function updateModGroupAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-group/%s/update', RhsModGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function updateModGroupAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/update', RhsModGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod group name', 'RHS');
        $I->seeInField('Mod group description', '');
        $I->seeTableRowCheckboxesAreUnchecked('mod_group_form_mods_', [
            RhsAfrfModFixture::ID,
            RhsGrefModFixture::ID,
            RhsUsafModFixture::ID,
        ]); // Some checkboxes checked

        // Fill form
        $I->fillField('Mod group name', 'CUP Terrains');
        $I->fillField('Mod group description', 'Terrains for CUP');
        $I->uncheckTableRowCheckbox(RhsAfrfModFixture::ID);
        $I->uncheckTableRowCheckbox(RhsGrefModFixture::ID);
        $I->uncheckTableRowCheckbox(RhsUsafModFixture::ID);
        $I->checkTableRowCheckbox(CupTerrainsCoreModFixture::ID);
        $I->checkTableRowCheckbox(CupTerrainsMapsModFixture::ID);
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/mod-group/list');

        /** @var ModGroup $modGroup */
        $modGroup = $I->grabEntityFromRepository(ModGroup::class, ['name' => 'CUP Terrains']);
        $I->assertSame('CUP Terrains', $modGroup->getName());
        $I->assertSame('Terrains for CUP', $modGroup->getDescription());
        $I->assertSame([
            CupTerrainsCoreModFixture::ID,
            CupTerrainsMapsModFixture::ID,
        ], array_map(fn (AbstractMod $mod) => $mod->getId()->toString(), $modGroup->getMods()));

        $I->assertSame('2020-01-01T00:00:00+00:00', $modGroup->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(null, $modGroup->getCreatedBy()?->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $modGroup->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modGroup->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateModGroupAsAuthorizedUserWhenModGroupAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/update', RhsModGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod group name', 'CUP');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'Mod group with the same name "CUP" already exist.');
    }

    public function updateModGroupAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/update', RhsModGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod group name', '');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function updateModGroupAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/update', RhsModGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod group name', str_repeat('a', 256));
        $I->fillField('Mod group description', str_repeat('a', 256));
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }

    public function updateModGroupAsAuthorizedUserWhenModGroupDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/update', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
