<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModGroup;

use App\Mods\DataFixtures\ModGroup\CupModGroupFixture;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class DeleteModGroupCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteModGroupAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod-group/%s/delete', CupModGroupFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(ModGroup::class, ['name' => CupModGroupFixture::NAME]);
    }

    public function deleteModGroupAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-group/%s/delete', CupModGroupFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(ModGroup::class, ['name' => CupModGroupFixture::NAME]);
    }

    public function deleteModGroupAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupDelete = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/delete', CupModGroupFixture::NAME));

        $I->seeResponseRedirectsTo('/mod-group/list');

        $I->dontSeeInRepository(ModGroup::class, ['name' => CupModGroupFixture::NAME]);
    }

    public function deleteModGroupAsAuthorizedUserWhenModGroupDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modGroupDelete = true;
        });

        $I->amOnPage(sprintf('/mod-group/%s/delete', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
