<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\ModGroup;

use App\DataFixtures\User\User1Fixture;
use App\Entity\User\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class ListModGroupsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listModGroupsAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod-group/list');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function listModGroupsAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod-group/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listModGroupsAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupList = true;
        });

        $I->amOnPage('/mod-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod group');
        $I->dontSeeActionButton('Edit mod group');
        $I->dontSeeActionButton('Delete mod group');
    }

    public function listModGroupsAsAuthorizedUserWithCreateModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupList = true;
            $user->getPermissions()->modGroupCreate = true;
        });

        $I->amOnPage('/mod-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create mod group');
        $I->dontSeeActionButton('Edit mod group');
        $I->dontSeeActionButton('Delete mod group');
    }

    public function listModGroupsAsAuthorizedUserWithUpdateModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupList = true;
            $user->getPermissions()->modGroupUpdate = true;
        });

        $I->amOnPage('/mod-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod group');
        $I->seeActionButton('Edit mod group');
        $I->dontSeeActionButton('Delete mod group');
    }

    public function listModGroupsAsAuthorizedUserWithDeleteModPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupList = true;
            $user->getPermissions()->modGroupDelete = true;
        });

        $I->amOnPage('/mod-group/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod group');
        $I->dontSeeActionButton('Edit mod group');
        $I->seeActionButton('Delete mod group');
    }
}
