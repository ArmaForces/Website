<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\UserGroup;

use App\DataFixtures\User\User1Fixture;
use App\DataFixtures\UserGroup\UsersGroupFixture;
use App\Entity\User\User;
use App\Entity\UserGroup\UserGroup;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class DeleteUserGroupCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteUserGroupAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/user-group/%s/delete', UsersGroupFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(UserGroup::class, ['name' => UsersGroupFixture::NAME]);
    }

    public function deleteUserGroupAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/user-group/%s/delete', UsersGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(UserGroup::class, ['name' => UsersGroupFixture::NAME]);
    }

    public function deleteUserGroupAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupDelete = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/delete', UsersGroupFixture::NAME));

        $I->seeResponseRedirectsTo('/user-group/list');

        $I->dontSeeInRepository(UserGroup::class, ['name' => UsersGroupFixture::NAME]);
    }

    public function deleteUserGroupAsAuthorizedUserWhenUserGroupDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->userGroupDelete = true;
        });

        $I->amOnPage(sprintf('/user-group/%s/delete', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
