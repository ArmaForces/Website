<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Web\Security;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;

class ConnectCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function logInAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/security/connect/discord');

        $I->seeResponseRedirectsToDiscordOauth();
    }

    public function logInAsRegisteredUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/security/connect/discord');

        $I->seeResponseRedirectsTo('/');
    }
}
