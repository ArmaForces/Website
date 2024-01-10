<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\User;

use App\DataFixtures\User\User1Fixture;
use App\Entity\User\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ListUsersCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listUsersAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/user/list');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function listUsersAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/user/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listUsersAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userList = true;
        });

        $I->amOnPage('/user/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeActionButton('Edit user');
        $I->dontSeeActionButton('Delete user');
    }

    public function listUsersAsAuthorizedUserWithUpdateUserPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userList = true;
            $user->getPermissions()->userUpdate = true;
        });

        $I->amOnPage('/user/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeActionButton('Edit user');
        $I->dontSeeActionButton('Delete user');
    }

    public function listUsersAsAuthorizedUserWithDeleteUserPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userList = true;
            $user->getPermissions()->userDelete = true;
        });

        $I->amOnPage('/user/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeActionButton('Edit user');
        $I->seeActionButton('Delete user');
        $I->dontSeeActionButton('Delete user', sprintf('/user/%s/delete', User1Fixture::ID));
    }
}
