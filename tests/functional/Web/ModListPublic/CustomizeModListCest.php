<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\ModListPublic;

use App\DataFixtures\ModList\DefaultModListFixture;
use App\DataFixtures\ModList\RhsModListFixture;
use App\DataFixtures\User\User1Fixture;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class CustomizeModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function customizeModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod-list/%s', DefaultModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeTableRowCheckboxesAreUnchecked('optional-mod-'); // All checkboxes are unchecked

        $I->seeLink('Download');
        $I->seeLink('Download required');
    }

    public function customizeModListAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-list/%s', DefaultModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeTableRowCheckboxesAreUnchecked('optional-mod-'); // All checkboxes are unchecked

        $I->seeLink('Download');
        $I->seeLink('Download required');
    }

    public function customizeModListAsAuthenticatedUserWhenModListInactive(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-list/%s', RhsModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
