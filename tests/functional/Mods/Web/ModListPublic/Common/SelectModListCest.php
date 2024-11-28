<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModListPublic\Common;

use App\Mods\DataFixtures\ModList\Standard\CupStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\RhsStandardModListFixture;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;

class SelectModListCest
{
    public function selectModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod-list/select');

        $I->see(DefaultStandardModListFixture::NAME);
        $I->see(CupStandardModListFixture::NAME);
        $I->dontSee(RhsStandardModListFixture::NAME); // disabled
    }

    public function selectModListAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod-list/select');

        $I->see(DefaultStandardModListFixture::NAME);
        $I->see(CupStandardModListFixture::NAME);
        $I->dontSee(RhsStandardModListFixture::NAME); // disabled
    }
}
