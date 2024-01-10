<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\User;

use App\DataFixtures\User\User1Fixture;
use App\DataFixtures\User\User2Fixture;
use App\Entity\User\User;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class DeleteUserCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteUserAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/user/%s/delete', User2Fixture::ID));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(User::class, ['id' => User2Fixture::ID]);
    }

    public function deleteUserAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/user/%s/delete', User2Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(User::class, ['id' => User2Fixture::ID]);
    }

    public function deleteUserAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userDelete = true;
        });

        $I->amOnPage(sprintf('/user/%s/delete', User2Fixture::ID));

        $I->seeResponseRedirectsTo('/user/list');

        $I->dontSeeInRepository(User::class, ['id' => User2Fixture::ID]);
    }

    public function deleteSelfAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userDelete = true;
        });

        $I->amOnPage(sprintf('/user/%s/delete', User1Fixture::ID));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(User::class, ['id' => User1Fixture::ID]);
    }

    public function deleteUserAsAuthorizedUserWhenUserDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userDelete = true;
        });

        $I->amOnPage(sprintf('/user/%s/delete', 'd2cca3c6-1c5f-4ea7-afcf-a05317a58467'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
