<?php

declare(strict_types=1);

namespace App\Tests\Functional\Users\Web\UserGroup;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class ListUserGroupsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listUserGroupsAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/user-group/list');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function listUserGroupsAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/user-group/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listUserGroupsAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupList = true;
        });

        $I->amOnPage('/user-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create user group');
        $I->dontSeeActionButton('Edit user group');
        $I->dontSeeActionButton('Delete user group');
    }

    public function listUserGroupsAsAuthorizedUserWithCreateModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupList = true;
            $user->getPermissions()->userGroupCreate = true;
        });

        $I->amOnPage('/user-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create user group');
        $I->dontSeeActionButton('Edit user group');
        $I->dontSeeActionButton('Delete user group');
    }

    public function listUserGroupsAsAuthorizedUserWithUpdateModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupList = true;
            $user->getPermissions()->userGroupUpdate = true;
        });

        $I->amOnPage('/user-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create user group');
        $I->seeActionButton('Edit user group');
        $I->dontSeeActionButton('Delete user group');
    }

    public function listUserGroupsAsAuthorizedUserWithDeleteModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupList = true;
            $user->getPermissions()->userGroupDelete = true;
        });

        $I->amOnPage('/user-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create user group');
        $I->dontSeeActionButton('Edit user group');
        $I->seeActionButton('Delete user group');
    }
}
