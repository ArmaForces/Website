<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\UserGroup;

use App\DataFixtures\User\AdminFixture;
use App\DataFixtures\User\User1Fixture;
use App\DataFixtures\User\User2Fixture;
use App\DataFixtures\User\User3Fixture;
use App\DataFixtures\UserGroup\UsersGroupFixture;
use App\Entity\User\User;
use App\Entity\UserGroup\UserGroup;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class CreateUserGroupCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();

        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function createUserGroupAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/user-group/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createUserGroupAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/user-group/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createUserGroupAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupCreate = true;
        });

        $I->amOnPage('/user-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('User group name', '');
        $I->seeInField('User group description', '');
        $I->seeTableRowCheckboxesAreUnchecked('user_group_form_users_'); // All checkboxes are unchecked
        $I->seeTableRowCheckboxesAreUnchecked('user_group_form_permissions_'); // All checkboxes are unchecked

        // Fill form
        $I->fillField('User group name', 'All');
        $I->fillField('User group description', 'All users');

        $I->checkTableRowCheckbox(AdminFixture::ID);
        $I->checkTableRowCheckbox(User1Fixture::ID);
        $I->checkTableRowCheckbox(User2Fixture::ID);
        $I->checkTableRowCheckbox(User3Fixture::ID);

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
        $I->click('Create user group');

        $I->seeResponseRedirectsTo('/user-group/list');

        /** @var UserGroup $userGroup */
        $userGroup = $I->grabEntityFromRepository(UserGroup::class, ['name' => 'All']);
        $I->assertSame('All', $userGroup->getName());
        $I->assertSame('All users', $userGroup->getDescription());
        $I->assertSame([
            AdminFixture::ID,
            User1Fixture::ID,
            User2Fixture::ID,
            User3Fixture::ID,
        ], array_map(fn (User $user) => $user->getId()->toString(), $userGroup->getUsers()));

        $I->assertTrue($userGroup->getPermissions()->userList);
        $I->assertTrue($userGroup->getPermissions()->userUpdate);
        $I->assertTrue($userGroup->getPermissions()->userDelete);

        $I->assertTrue($userGroup->getPermissions()->userGroupList);
        $I->assertTrue($userGroup->getPermissions()->userGroupCreate);
        $I->assertTrue($userGroup->getPermissions()->userGroupUpdate);
        $I->assertTrue($userGroup->getPermissions()->userGroupDelete);

        $I->assertTrue($userGroup->getPermissions()->modList);
        $I->assertTrue($userGroup->getPermissions()->modCreate);
        $I->assertTrue($userGroup->getPermissions()->modUpdate);
        $I->assertTrue($userGroup->getPermissions()->modDelete);
        $I->assertTrue($userGroup->getPermissions()->modChangeStatus);

        $I->assertTrue($userGroup->getPermissions()->modGroupList);
        $I->assertTrue($userGroup->getPermissions()->modGroupCreate);
        $I->assertTrue($userGroup->getPermissions()->modGroupUpdate);
        $I->assertTrue($userGroup->getPermissions()->modGroupDelete);

        $I->assertTrue($userGroup->getPermissions()->dlcList);
        $I->assertTrue($userGroup->getPermissions()->dlcCreate);
        $I->assertTrue($userGroup->getPermissions()->dlcUpdate);
        $I->assertTrue($userGroup->getPermissions()->dlcDelete);

        $I->assertTrue($userGroup->getPermissions()->modListList);
        $I->assertTrue($userGroup->getPermissions()->modListCreate);
        $I->assertTrue($userGroup->getPermissions()->modListUpdate);
        $I->assertTrue($userGroup->getPermissions()->modListCopy);
        $I->assertTrue($userGroup->getPermissions()->modListDelete);
        $I->assertTrue($userGroup->getPermissions()->modListApprove);

        $I->assertSame('2020-01-01T00:00:00+00:00', $userGroup->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $userGroup->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $userGroup->getLastUpdatedAt());
        $I->assertSame(null, $userGroup->getLastUpdatedBy()?->getId()->toString());
    }

    public function createUserGroupAsAuthorizedUserWhenUserGroupAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupCreate = true;
        });

        $I->amOnPage('/user-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('User group name', UsersGroupFixture::NAME);
        $I->click('Create user group');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'User group with the same name "Users" already exist.');
    }

    public function createUserGroupAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupCreate = true;
        });

        $I->amOnPage('/user-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('User group name', '');
        $I->click('Create user group');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function createUserGroupAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupCreate = true;
        });

        $I->amOnPage('/user-group/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('User group name', str_repeat('a', 256));
        $I->fillField('User group description', str_repeat('a', 256));
        $I->click('Create user group');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }
}
