<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\External;

use App\Mods\DataFixtures\ModList\External\GoogleExternalModList;
use App\Mods\Entity\ModList\ExternalModList;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class DeleteExternalModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteExternalModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/external-mod-list/%s/delete', GoogleExternalModList::NAME));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(ExternalModList::class, ['name' => GoogleExternalModList::NAME]);
    }

    public function deleteExternalModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/external-mod-list/%s/delete', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(ExternalModList::class, ['name' => GoogleExternalModList::NAME]);
    }

    public function deleteExternalModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListDelete = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/delete', GoogleExternalModList::NAME));

        $I->seeResponseRedirectsTo('/mod-list/list');

        $I->dontSeeInRepository(ExternalModList::class, ['name' => GoogleExternalModList::NAME]);
    }

    public function deleteExternalModListAsAuthorizedUserWhenModListDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListDelete = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/delete', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
