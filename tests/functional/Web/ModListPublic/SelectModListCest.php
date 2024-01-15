<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\ModListPublic;

use App\DataFixtures\ModList\CupModListFixture;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\DataFixtures\ModList\RhsModListFixture;
use App\DataFixtures\User\User1Fixture;
use App\Tests\FunctionalTester;

class SelectModListCest
{
    public function selectModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod-list/select');

        $I->see(DefaultModListFixture::NAME);
        $I->see(CupModListFixture::NAME);
        $I->dontSee(RhsModListFixture::NAME); // disabled
    }

    public function selectModListAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod-list/select');

        $I->see(DefaultModListFixture::NAME);
        $I->see(CupModListFixture::NAME);
        $I->dontSee(RhsModListFixture::NAME); // disabled
    }
}
