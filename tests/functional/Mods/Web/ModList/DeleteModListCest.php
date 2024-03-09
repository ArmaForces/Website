<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList;

use App\Mods\DataFixtures\ModList\CupModListFixture;
use App\Mods\DataFixtures\ModList\DefaultModListFixture;
use App\Mods\Entity\ModList\ModList;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class DeleteModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod-list/%s/delete', DefaultModListFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(ModList::class, ['name' => DefaultModListFixture::NAME]);
    }

    public function deleteModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-list/%s/delete', DefaultModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(ModList::class, ['name' => DefaultModListFixture::NAME]);
    }

    public function deleteModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListDelete = true;
        });

        $I->amOnPage(sprintf('/mod-list/%s/delete', DefaultModListFixture::NAME));

        $I->seeResponseRedirectsTo('/mod-list/list');

        $I->dontSeeInRepository(ModList::class, ['name' => DefaultModListFixture::NAME]);
    }

    public function deleteModListAsModListOwner(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(CupModListFixture::OWNER_ID, function (User $user): void {
            $user->getPermissions()->modListDelete = false;
        });

        $I->amOnPage(sprintf('/mod-list/%s/delete', CupModListFixture::NAME));

        $I->seeResponseRedirectsTo('/mod-list/list');

        $I->dontSeeInRepository(ModList::class, ['name' => CupModListFixture::NAME]);
    }

    public function deleteModListAsAuthorizedUserWhenModListDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListDelete = true;
        });

        $I->amOnPage(sprintf('/mod-list/%s/delete', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
