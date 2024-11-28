<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\Standard;

use App\Mods\DataFixtures\ModList\Standard\CupStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\Entity\ModList\StandardModList;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class DeleteStandardModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteStandardModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/standard-mod-list/%s/delete', DefaultStandardModListFixture::NAME));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(StandardModList::class, ['name' => DefaultStandardModListFixture::NAME]);
    }

    public function deleteStandardModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/standard-mod-list/%s/delete', DefaultStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(StandardModList::class, ['name' => DefaultStandardModListFixture::NAME]);
    }

    public function deleteStandardModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListDelete = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/delete', DefaultStandardModListFixture::NAME));

        $I->seeResponseRedirectsTo('/mod-list/list');

        $I->dontSeeInRepository(StandardModList::class, ['name' => DefaultStandardModListFixture::NAME]);
    }

    public function deleteStandardModListAsModListOwner(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(CupStandardModListFixture::OWNER_ID, function (User $user): void {
            $user->getPermissions()->standardModListDelete = false;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/delete', CupStandardModListFixture::NAME));

        $I->seeResponseRedirectsTo('/mod-list/list');

        $I->dontSeeInRepository(StandardModList::class, ['name' => CupStandardModListFixture::NAME]);
    }

    public function deleteStandardModListAsAuthorizedUserWhenModListDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->standardModListDelete = true;
        });

        $I->amOnPage(sprintf('/standard-mod-list/%s/delete', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
