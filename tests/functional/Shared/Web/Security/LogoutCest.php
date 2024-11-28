<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Web\Security;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;

class LogoutCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function logOutAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/security/logout');
        $I->seeResponseRedirectsTo('http://localhost/');
    }

    public function logOutAsRegisteredUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/security/logout');
        $I->seeResponseRedirectsTo('http://localhost/');

        $I->dontSeeAuthentication();
    }
}
