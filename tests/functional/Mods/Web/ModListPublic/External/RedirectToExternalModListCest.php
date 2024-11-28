<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModListPublic\External;

use App\Mods\DataFixtures\ModList\External\GoogleExternalModList;
use App\Mods\DataFixtures\ModList\External\LocalhostExternalModList;
use App\Mods\Entity\ModList\ExternalModList;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use Codeception\Util\HttpCode;

class RedirectToExternalModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function redirectToExternalModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        /** @var ExternalModList $externalModList */
        $externalModList = $I->grabEntityFromRepository(ExternalModList::class, ['name' => LocalhostExternalModList::NAME]);

        $I->stopFollowingRedirects();
        $I->amOnPage(sprintf('/mod-list/%s', $externalModList->getName()));

        $I->seeResponseRedirectsTo($externalModList->getUrl());
    }

    public function redirectToExternalModListAsAuthenticatedUser(FunctionalTester $I): void
    {
        /** @var ExternalModList $externalModList */
        $externalModList = $I->grabEntityFromRepository(ExternalModList::class, ['name' => LocalhostExternalModList::NAME]);

        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->stopFollowingRedirects();
        $I->amOnPage(sprintf('/mod-list/%s', $externalModList->getName()));

        $I->seeResponseRedirectsTo($externalModList->getUrl());
    }

    public function redirectToExternalModListAsAuthenticatedUserWhenModListInactive(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-list/%s', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
