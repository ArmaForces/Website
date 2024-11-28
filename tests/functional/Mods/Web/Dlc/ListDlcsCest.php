<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\Dlc;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class ListDlcsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listDlcsAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/dlc/list');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function listDlcsAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/dlc/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listDlcsAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcList = true;
        });

        $I->amOnPage('/dlc/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeActionButton('Create DLC');
        $I->dontSeeActionButton('Edit DLC');
    }

    public function listDlcsAsAuthorizedUserWithCreateDlcPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcList = true;
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create DLC');
        $I->dontSeeActionButton('Edit DLC');
        $I->dontSeeActionButton('Delete DLC');
    }

    public function listDlcsAsAuthorizedUserWithUpdateDlcPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcList = true;
            $user->getPermissions()->dlcUpdate = true;
        });

        $I->amOnPage('/dlc/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create DLC');
        $I->seeActionButton('Edit DLC');
        $I->dontSeeActionButton('Delete DLC');
    }

    public function listDlcsAsAuthorizedUserWithDeleteDlcPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcList = true;
            $user->getPermissions()->dlcDelete = true;
        });

        $I->amOnPage('/dlc/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create DLC');
        $I->dontSeeActionButton('Edit DLC');
        $I->seeActionButton('Delete DLC');
    }
}
