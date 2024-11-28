<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Web\Home;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\AdminFixture;
use App\Users\DataFixtures\User\User1Fixture;
use Codeception\Util\HttpCode;

class JoinUsCest
{
    public function visitJoinUsPageAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/join-us');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsUnauthenticatedUser();

        $I->seeElement('a[href="https://drive.google.com/file/d/1a2Ote4EegVOxYUP7Un9gosLzBSmWV8fj"]');
        $I->seeElement('a[href="https://discord.gg/wcuVSrU"]');
        $I->seeElement('a[href="#"]');
        $I->seeElement('a[href="/mod-list/Default"]');

        $I->seePageFooter();
    }

    public function visitJoinUsPageAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/join-us');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsAuthenticatedUser();

        $I->seeElement('a[href="https://drive.google.com/file/d/1a2Ote4EegVOxYUP7Un9gosLzBSmWV8fj"]');
        $I->seeElement('a[href="https://discord.gg/wcuVSrU"]');
        $I->seeElement('a[href="ts3server://ts.localhost.com?password=test"]');
        $I->seeElement('a[href="/mod-list/Default"]');

        $I->seePageFooter();
    }

    public function visitJoinUsPageAsAuthenticatedUserWithFullPermissions(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(AdminFixture::ID);

        $I->amOnPage('/join-us');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsAdmin();

        $I->seeElement('a[href="https://drive.google.com/file/d/1a2Ote4EegVOxYUP7Un9gosLzBSmWV8fj"]');
        $I->seeElement('a[href="https://discord.gg/wcuVSrU"]');
        $I->seeElement('a[href="ts3server://ts.localhost.com?password=test"]');
        $I->seeElement('a[href="/mod-list/Default"]');

        $I->seePageFooter();
    }
}
