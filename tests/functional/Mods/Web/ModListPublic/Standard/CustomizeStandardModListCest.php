<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModListPublic\Standard;

use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\RhsStandardModListFixture;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use Codeception\Util\HttpCode;

class CustomizeStandardModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function customizeModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeTableRowCheckboxesAreUnchecked('optional-mod-'); // All checkboxes are unchecked

        $I->seeLink('Download');
        $I->seeLink('Download required');
    }

    public function customizeModListAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeTableRowCheckboxesAreUnchecked('optional-mod-'); // All checkboxes are unchecked

        $I->seeLink('Download');
        $I->seeLink('Download required');
    }

    public function customizeModListAsAuthenticatedUserWhenModListInactive(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }
}
