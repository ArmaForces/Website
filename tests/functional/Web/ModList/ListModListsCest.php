<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\ModList;

use App\DataFixtures\ModList\CupModListFixture;
use App\DataFixtures\User\User3Fixture;
use App\Entity\User\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ListModListsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listModListsAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod-list/list');
        $I->seeResponseRedirectsToLogInAction();
    }

    public function listModListsAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID);

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listModListsAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->seeActionButton('Download mod list');
        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithCreateModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->modListCreate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create mod list');
        $I->seeActionButton('Download mod list');
        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithUpdateModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->modListUpdate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->seeActionButton('Download mod list');
        $I->seeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithModListOwnership(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(CupModListFixture::OWNER_ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->seeActionButton('Download mod list');
        $I->seeActionButton('Edit mod list', '/mod-list/CUP/update');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->seeActionButton('Delete mod list', '/mod-list/CUP/delete');
    }

    public function listModListsAsAuthorizedUserWithCopyModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->modListCopy = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->seeActionButton('Download mod list');
        $I->dontSeeActionButton('Edit mod list');
        $I->seeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithDeleteModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->modListDelete = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->seeActionButton('Download mod list');
        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->seeActionButton('Delete mod list');
    }
}
