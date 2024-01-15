<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\Mod;

use App\DataFixtures\User\User1Fixture;
use App\Entity\User\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ListModsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listModsAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod/list');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function listModsAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listModsAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modList = true;
        });

        $I->amOnPage('/mod/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod');
        $I->dontSeeActionButton('Edit mod');
        $I->dontSeeActionButton('Delete mod');
    }

    public function listModsAsAuthorizedUserWithCreateModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modList = true;
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create mod');
        $I->dontSeeActionButton('Edit mod');
        $I->dontSeeActionButton('Delete mod');
    }

    public function listModsAsAuthorizedUserWithUpdateModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modList = true;
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage('/mod/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod');
        $I->seeActionButton('Edit mod');
        $I->dontSeeActionButton('Delete mod');
    }

    public function listModsAsAuthorizedUserWithDeleteModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modList = true;
            $user->getPermissions()->modDelete = true;
        });

        $I->amOnPage('/mod/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod');
        $I->dontSeeActionButton('Edit mod');
        $I->seeActionButton('Delete mod');
    }
}
