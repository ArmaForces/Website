<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\Api;

use App\DataFixtures\User\AdminFixture;
use App\DataFixtures\User\User1Fixture;
use App\Tests\FunctionalTester;

class IndexCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->haveHttpHeader('Accept', 'text/html');
    }

    public function visitApiDocsPageAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/api');

        $I->seeResponseCodeIs(200);
        $I->see('ArmaForces Website Web API');
    }

    public function visitApiDocsPageAsRegisteredUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/api');

        $I->seeResponseCodeIs(200);
        $I->see('ArmaForces Website Web API');
    }

    public function visitApiDocsPageAsRegisteredUserWithFullPermissions(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(AdminFixture::ID);

        $I->amOnPage('/api');

        $I->seeResponseCodeIs(200);
        $I->see('ArmaForces Website Web API');
    }
}
