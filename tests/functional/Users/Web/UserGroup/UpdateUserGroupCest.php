<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users\Web\UserGroup;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\AdminFixture;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\DataFixtures\User\User2Fixture;
use App\Users\DataFixtures\User\User3Fixture;
use App\Users\DataFixtures\UserGroup\AdminsGroupFixture;
use App\Users\DataFixtures\UserGroup\UsersGroupFixture;
use App\Users\Entity\User\User;
use App\Users\Entity\UserGroup\UserGroup;
use Codeception\Util\HttpCode;

class UpdateUserGroupCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2021-01-01T00:00:00+00:00');
    }

    public function updateUserGroupAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/user-group/%s/update', UsersGroupFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();
    }

    public function updateUserGroupAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/user-group/%s/update', UsersGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function updateUserGroupAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/update', UsersGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('User group name', 'Users');
        $I->seeInField('User group description', '');
        $I->seeTableRowCheckboxesAreUnchecked('user_group_form_users_', [
            User1Fixture::ID,
            User2Fixture::ID,
            User3Fixture::ID,
        ]); // Some checkboxes checked
        $I->seeTableRowCheckboxesAreUnchecked('user_group_form_permissions_'); // Some checkboxes checked

        // Fill form
        $I->fillField('User group name', 'All');
        $I->fillField('User group description', 'All users');
        $I->checkTableRowCheckbox(AdminFixture::ID);
        $I->checkOption('Can list users');
        $I->checkOption('Can list user groups');
        $I->checkOption('Can list mods');
        $I->checkOption('Can list mod groups');
        $I->checkOption('Can list DLCs');
        $I->checkOption('Can list mod lists');
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/user-group/list');

        /** @var UserGroup $userGroup */
        $userGroup = $I->grabEntityFromRepository(UserGroup::class, ['name' => 'All']);
        $I->assertSame('All', $userGroup->getName());
        $I->assertSame('All users', $userGroup->getDescription());

        $groupUserIds = array_map(fn (User $user) => $user->getId()->toString(), $userGroup->getUsers());
        $expectedGroupUserIds = [
            User1Fixture::ID,
            User2Fixture::ID,
            User3Fixture::ID,
            AdminFixture::ID,
        ];
        $I->assertArraySame($expectedGroupUserIds, $groupUserIds);

        $I->assertTrue($userGroup->getPermissions()->userList);
        $I->assertFalse($userGroup->getPermissions()->userUpdate);
        $I->assertFalse($userGroup->getPermissions()->userDelete);

        $I->assertTrue($userGroup->getPermissions()->userGroupList);
        $I->assertFalse($userGroup->getPermissions()->userGroupCreate);
        $I->assertFalse($userGroup->getPermissions()->userGroupUpdate);
        $I->assertFalse($userGroup->getPermissions()->userGroupDelete);

        $I->assertTrue($userGroup->getPermissions()->modList);
        $I->assertFalse($userGroup->getPermissions()->modCreate);
        $I->assertFalse($userGroup->getPermissions()->modUpdate);
        $I->assertFalse($userGroup->getPermissions()->modDelete);
        $I->assertFalse($userGroup->getPermissions()->modChangeStatus);

        $I->assertTrue($userGroup->getPermissions()->modGroupList);
        $I->assertFalse($userGroup->getPermissions()->modGroupCreate);
        $I->assertFalse($userGroup->getPermissions()->modGroupUpdate);
        $I->assertFalse($userGroup->getPermissions()->modGroupDelete);

        $I->assertTrue($userGroup->getPermissions()->dlcList);
        $I->assertFalse($userGroup->getPermissions()->dlcCreate);
        $I->assertFalse($userGroup->getPermissions()->dlcUpdate);
        $I->assertFalse($userGroup->getPermissions()->dlcDelete);

        $I->assertTrue($userGroup->getPermissions()->modListList);
        $I->assertFalse($userGroup->getPermissions()->standardModListCreate);
        $I->assertFalse($userGroup->getPermissions()->standardModListUpdate);
        $I->assertFalse($userGroup->getPermissions()->standardModListCopy);
        $I->assertFalse($userGroup->getPermissions()->standardModListDelete);
        $I->assertFalse($userGroup->getPermissions()->standardModListApprove);

        $I->assertSame('2020-01-01T00:00:00+00:00', $userGroup->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(null, $userGroup->getCreatedBy()?->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $userGroup->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $userGroup->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateUserGroupAsAuthorizedUserWhenUserGroupAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/update', UsersGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('User group name', AdminsGroupFixture::NAME);
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'User group with the same name "Admins" already exist.');
    }

    public function updateUserGroupAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/update', UsersGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('User group name', '');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function updateUserGroupAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/update', UsersGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('User group name', str_repeat('a', 256));
        $I->fillField('User group description', str_repeat('a', 256));
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }

    public function updateUserGroupAsAuthorizedUserWhenUserGroupDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupUpdate = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/update', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
