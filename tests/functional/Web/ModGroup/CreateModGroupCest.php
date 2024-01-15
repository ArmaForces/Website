<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\ModGroup;

use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsCoreModFixture;
use App\DataFixtures\Mod\SteamWorkshop\Required\CupTerrainsMapsModFixture;
use App\DataFixtures\User\User1Fixture;
use App\Entity\Mod\AbstractMod;
use App\Entity\ModGroup\ModGroup;
use App\Entity\User\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class CreateModGroupCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function createModGroupAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod-group/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createModGroupAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod-group/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createModGroupAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupCreate = true;
        });

        $I->amOnPage('/mod-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod group name', '');
        $I->seeInField('Mod group description', '');
        $I->seeTableRowCheckboxesAreUnchecked('mod_group_form_mods_'); // All checkboxes are unchecked

        // Fill form
        $I->fillField('Mod group name', 'CUP Terrains');
        $I->fillField('Mod group description', 'Terrains for CUP');
        $I->checkTableRowCheckbox(CupTerrainsCoreModFixture::ID);
        $I->checkTableRowCheckbox(CupTerrainsMapsModFixture::ID);
        $I->click('Create mod group');

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
        $I->assertSame($currentUser->getId()->toString(), $modGroup->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modGroup->getLastUpdatedAt());
        $I->assertSame(null, $modGroup->getLastUpdatedBy()?->getId()->toString());
    }

    public function createModGroupAsAuthorizedUserWhenModGroupAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupCreate = true;
        });

        $I->amOnPage('/mod-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod group name', 'CUP');
        $I->click('Create mod group');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'Mod group with the same name "CUP" already exist.');
    }

    public function createModGroupAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupCreate = true;
        });

        $I->amOnPage('/mod-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod group name', '');
        $I->click('Create mod group');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function createModGroupAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupCreate = true;
        });

        $I->amOnPage('/mod-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod group name', str_repeat('a', 256));
        $I->fillField('Mod group description', str_repeat('a', 256));
        $I->click('Create mod group');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }
}