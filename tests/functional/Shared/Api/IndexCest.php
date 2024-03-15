<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Api;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\AdminFixture;
use App\Users\DataFixtures\User\User1Fixture;

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
