<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\Home;

use App\DataFixtures\User\AdminFixture;
use App\DataFixtures\User\User1Fixture;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class IndexCest
{
    public function visitHomePageAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsUnauthenticatedUser();

        $I->seeLink('About us');
        $I->seeLink('Join us!');

        $I->seeElement('a[href="https://docs.google.com/spreadsheets/d/1t1158AsoxIwXI5FlPNjqbaXk6Cx7oq7Ocgchnsk2_TE"]');

        $I->seePageFooter();
    }

    public function visitHomePageAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsAuthenticatedUser();

        $I->seeLink('About us');
        $I->seeLink('Join us!');

        $I->seeElement('a[href="https://docs.google.com/spreadsheets/d/1t1158AsoxIwXI5FlPNjqbaXk6Cx7oq7Ocgchnsk2_TE"]');

        $I->seePageFooter();
    }

    public function visitHomePageAsAuthenticatedUserWithFullPermissions(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(AdminFixture::ID);

        $I->amOnPage('/');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsAdmin();

        $I->seeLink('About us');
        $I->seeLink('Join us!');

        $I->seeElement('a[href="https://docs.google.com/spreadsheets/d/1t1158AsoxIwXI5FlPNjqbaXk6Cx7oq7Ocgchnsk2_TE"]');

        $I->seePageFooter();
    }
}
