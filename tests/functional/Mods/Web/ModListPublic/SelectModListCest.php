<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModListPublic;

use App\Mods\DataFixtures\ModList\CupModListFixture;
use App\Mods\DataFixtures\ModList\DefaultModListFixture;
use App\Mods\DataFixtures\ModList\RhsModListFixture;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;

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
