<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users\Web\User;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\DataFixtures\User\User2Fixture;
use App\Users\DataFixtures\User\User3Fixture;
use App\Users\Entity\User\User;
use Codeception\Attribute\DataProvider;
use Codeception\Example;
use Codeception\Util\HttpCode;

class UpdateUserCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2021-01-01T00:00:00+00:00');
    }

    public function updateUserAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/user/%s/update', User2Fixture::ID));

        $I->seeResponseRedirectsToLogInAction();
    }

    public function updateUserAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/user/%s/update', User2Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function updateUserAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userUpdate = true;
        });

        $I->amOnPage(sprintf('/user/%s/update', User2Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Steam ID', User2Fixture::STEAM_ID);

        $I->dontSeeCheckboxIsChecked('Can list users');
        $I->dontSeeCheckboxIsChecked('Can edit users');
        $I->dontSeeCheckboxIsChecked('Can delete users');

        $I->dontSeeCheckboxIsChecked('Can list user groups');
        $I->dontSeeCheckboxIsChecked('Can create user groups');
        $I->dontSeeCheckboxIsChecked('Can edit user groups');
        $I->dontSeeCheckboxIsChecked('Can delete user groups');

        $I->dontSeeCheckboxIsChecked('Can list mods');
        $I->dontSeeCheckboxIsChecked('Can create mods');
        $I->dontSeeCheckboxIsChecked('Can edit mods');
        $I->dontSeeCheckboxIsChecked('Can delete mods');
        $I->dontSeeCheckboxIsChecked('Can change mods status');

        $I->dontSeeCheckboxIsChecked('Can list mod groups');
        $I->dontSeeCheckboxIsChecked('Can create mod groups');
        $I->dontSeeCheckboxIsChecked('Can edit mod groups');
        $I->dontSeeCheckboxIsChecked('Can delete mod groups');

        $I->dontSeeCheckboxIsChecked('Can list DLCs');
        $I->dontSeeCheckboxIsChecked('Can create DLCs');
        $I->dontSeeCheckboxIsChecked('Can edit DLCs');
        $I->dontSeeCheckboxIsChecked('Can delete DLCs');

        $I->dontSeeCheckboxIsChecked('Can list mod lists');
        $I->dontSeeCheckboxIsChecked('Can create mod lists');
        $I->dontSeeCheckboxIsChecked('Can edit other users mod lists');
        $I->dontSeeCheckboxIsChecked('Can copy mod lists');
        $I->dontSeeCheckboxIsChecked('Can delete other users mod lists');
        $I->dontSeeCheckboxIsChecked('Can approve mod lists');

        // Fill form
        $I->fillField('Steam ID', 12345678901234567);

        $I->checkOption('Can list users');
        $I->checkOption('Can edit users');
        $I->checkOption('Can delete users');

        $I->checkOption('Can list user groups');
        $I->checkOption('Can create user groups');
        $I->checkOption('Can edit user groups');
        $I->checkOption('Can delete user groups');

        $I->checkOption('Can list mods');
        $I->checkOption('Can create mods');
        $I->checkOption('Can edit mods');
        $I->checkOption('Can delete mods');
        $I->checkOption('Can change mods status');

        $I->checkOption('Can list mod groups');
        $I->checkOption('Can create mod groups');
        $I->checkOption('Can edit mod groups');
        $I->checkOption('Can delete mod groups');

        $I->checkOption('Can list DLCs');
        $I->checkOption('Can create DLCs');
        $I->checkOption('Can edit DLCs');
        $I->checkOption('Can delete DLCs');

        $I->checkOption('Can list mod lists');
        $I->checkOption('Can create mod lists');
        $I->checkOption('Can edit other users mod lists');
        $I->checkOption('Can copy mod lists');
        $I->checkOption('Can delete other users mod lists');
        $I->checkOption('Can approve mod lists');
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/user/list');

        /** @var User $user */
        $user = $I->grabEntityFromRepository(User::class, ['id' => User2Fixture::ID]);
        $I->assertSame(12345678901234567, $user->getSteamId());

        $I->assertTrue($user->getPermissions()->userList);
        $I->assertTrue($user->getPermissions()->userUpdate);
        $I->assertTrue($user->getPermissions()->userDelete);

        $I->assertTrue($user->getPermissions()->userGroupList);
        $I->assertTrue($user->getPermissions()->userGroupCreate);
        $I->assertTrue($user->getPermissions()->userGroupUpdate);
        $I->assertTrue($user->getPermissions()->userGroupDelete);

        $I->assertTrue($user->getPermissions()->modList);
        $I->assertTrue($user->getPermissions()->modCreate);
        $I->assertTrue($user->getPermissions()->modUpdate);
        $I->assertTrue($user->getPermissions()->modDelete);
        $I->assertTrue($user->getPermissions()->modChangeStatus);

        $I->assertTrue($user->getPermissions()->modGroupList);
        $I->assertTrue($user->getPermissions()->modGroupCreate);
        $I->assertTrue($user->getPermissions()->modGroupUpdate);
        $I->assertTrue($user->getPermissions()->modGroupDelete);

        $I->assertTrue($user->getPermissions()->dlcList);
        $I->assertTrue($user->getPermissions()->dlcCreate);
        $I->assertTrue($user->getPermissions()->dlcUpdate);
        $I->assertTrue($user->getPermissions()->dlcDelete);

        $I->assertTrue($user->getPermissions()->modListList);
        $I->assertTrue($user->getPermissions()->standardModListCreate);
        $I->assertTrue($user->getPermissions()->standardModListUpdate);
        $I->assertTrue($user->getPermissions()->standardModListCopy);
        $I->assertTrue($user->getPermissions()->standardModListDelete);
        $I->assertTrue($user->getPermissions()->standardModListApprove);

        $I->assertSame('2020-01-01T00:00:00+00:00', $user->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(null, $user->getCreatedBy()?->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $user->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $user->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateSelfAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userList = true;
            $user->getPermissions()->userUpdate = true;
        });

        $I->amOnPage(sprintf('/user/%s/update', User1Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Steam ID', User1Fixture::STEAM_ID);

        $I->seeElement('#user_form_permissions_userList[disabled][checked]');
        $I->seeElement('#user_form_permissions_userUpdate[disabled][checked]');
        $I->dontSeeCheckboxIsChecked('Can delete users');

        $I->dontSeeCheckboxIsChecked('Can list user groups');
        $I->dontSeeCheckboxIsChecked('Can create user groups');
        $I->dontSeeCheckboxIsChecked('Can edit user groups');
        $I->dontSeeCheckboxIsChecked('Can delete user groups');

        $I->dontSeeCheckboxIsChecked('Can list mods');
        $I->dontSeeCheckboxIsChecked('Can create mods');
        $I->dontSeeCheckboxIsChecked('Can edit mods');
        $I->dontSeeCheckboxIsChecked('Can delete mods');
        $I->dontSeeCheckboxIsChecked('Can change mods status');

        $I->dontSeeCheckboxIsChecked('Can list mod groups');
        $I->dontSeeCheckboxIsChecked('Can create mod groups');
        $I->dontSeeCheckboxIsChecked('Can edit mod groups');
        $I->dontSeeCheckboxIsChecked('Can delete mod groups');

        $I->dontSeeCheckboxIsChecked('Can list DLCs');
        $I->dontSeeCheckboxIsChecked('Can create DLCs');
        $I->dontSeeCheckboxIsChecked('Can edit DLCs');
        $I->dontSeeCheckboxIsChecked('Can delete DLCs');

        $I->dontSeeCheckboxIsChecked('Can list mod lists');
        $I->dontSeeCheckboxIsChecked('Can create mod lists');
        $I->dontSeeCheckboxIsChecked('Can edit other users mod lists');
        $I->dontSeeCheckboxIsChecked('Can copy mod lists');
        $I->dontSeeCheckboxIsChecked('Can delete other users mod lists');
        $I->dontSeeCheckboxIsChecked('Can approve mod lists');

        // Fill form
        $I->fillField('Steam ID', 12345678901234567);

        $I->checkOption('Can delete users');

        $I->checkOption('Can list user groups');
        $I->checkOption('Can create user groups');
        $I->checkOption('Can edit user groups');
        $I->checkOption('Can delete user groups');

        $I->checkOption('Can list mods');
        $I->checkOption('Can create mods');
        $I->checkOption('Can edit mods');
        $I->checkOption('Can delete mods');
        $I->checkOption('Can change mods status');

        $I->checkOption('Can list mod groups');
        $I->checkOption('Can create mod groups');
        $I->checkOption('Can edit mod groups');
        $I->checkOption('Can delete mod groups');

        $I->checkOption('Can list DLCs');
        $I->checkOption('Can create DLCs');
        $I->checkOption('Can edit DLCs');
        $I->checkOption('Can delete DLCs');

        $I->checkOption('Can list mod lists');
        $I->checkOption('Can create mod lists');
        $I->checkOption('Can edit other users mod lists');
        $I->checkOption('Can copy mod lists');
        $I->checkOption('Can delete other users mod lists');
        $I->checkOption('Can approve mod lists');
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/user/list');

        /** @var User $user */
        $user = $I->grabEntityFromRepository(User::class, ['id' => User1Fixture::ID]);
        $I->assertSame(12345678901234567, $user->getSteamId());

        $I->assertTrue($user->getPermissions()->userDelete);

        $I->assertTrue($user->getPermissions()->userGroupList);
        $I->assertTrue($user->getPermissions()->userGroupCreate);
        $I->assertTrue($user->getPermissions()->userGroupUpdate);
        $I->assertTrue($user->getPermissions()->userGroupDelete);

        $I->assertTrue($user->getPermissions()->modList);
        $I->assertTrue($user->getPermissions()->modCreate);
        $I->assertTrue($user->getPermissions()->modUpdate);
        $I->assertTrue($user->getPermissions()->modDelete);
        $I->assertTrue($user->getPermissions()->modChangeStatus);

        $I->assertTrue($user->getPermissions()->modGroupList);
        $I->assertTrue($user->getPermissions()->modGroupCreate);
        $I->assertTrue($user->getPermissions()->modGroupUpdate);
        $I->assertTrue($user->getPermissions()->modGroupDelete);

        $I->assertTrue($user->getPermissions()->dlcList);
        $I->assertTrue($user->getPermissions()->dlcCreate);
        $I->assertTrue($user->getPermissions()->dlcUpdate);
        $I->assertTrue($user->getPermissions()->dlcDelete);

        $I->assertTrue($user->getPermissions()->modListList);
        $I->assertTrue($user->getPermissions()->standardModListCreate);
        $I->assertTrue($user->getPermissions()->standardModListUpdate);
        $I->assertTrue($user->getPermissions()->standardModListCopy);
        $I->assertTrue($user->getPermissions()->standardModListDelete);
        $I->assertTrue($user->getPermissions()->standardModListApprove);

        $I->assertSame('2020-01-01T00:00:00+00:00', $user->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(null, $user->getCreatedBy()?->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $user->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $user->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateUserAsAuthorizedUserWhenUserAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userUpdate = true;
        });

        $I->amOnPage(sprintf('/user/%s/update', User2Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam ID', User3Fixture::STEAM_ID);
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('steamId', 'User with the same Steam ID "33333333333333333" already exist.');
    }

    #[DataProvider('invalidSteamIdDataProvider')]
    public function updateUserAsAuthorizedUserWithInvalidSteamId(FunctionalTester $I, Example $example): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userUpdate = true;
        });

        $I->amOnPage(sprintf('/user/%s/update', User2Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam ID', $example['steamId']);
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('steamId', $example['error']);
    }

    public function updateUserAsAuthorizedUserWhenUserDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userUpdate = true;
        });

        $I->amOnPage(sprintf('/user/%s/update', 'd2cca3c6-1c5f-4ea7-afcf-a05317a58467'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }

    protected function invalidSteamIdDataProvider(): array
    {
        return [
            ['steamId' => 'asd', 'error' => 'Please enter a number.'],
            ['steamId' => '123', 'error' => 'Invalid Steam profile ID.'],
            ['steamId' => '1234567890123456789', 'error' => 'Invalid Steam profile ID.'],
        ];
    }
}
