<?php

declare(strict_types=1);

namespace App\Tests\Functional\Shared\Web\Home;

use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\AdminFixture;
use App\Users\DataFixtures\User\User1Fixture;
use Codeception\Util\HttpCode;

class MissionsCest
{
    public function visitMissionsPageAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/missions');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsUnauthenticatedUser();

        $I->see('Upcoming missions');
        $I->see('Completed missions');

        $I->seePageFooter();
    }

    public function visitMissionsPageAsAuthenticatedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/missions');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsAuthenticatedUser();

        $I->see('Upcoming missions');
        $I->see('Completed missions');

        $I->seePageFooter();
    }

    public function visitMissionsPageAsAuthenticatedUserWithFullPermissions(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(AdminFixture::ID);

        $I->amOnPage('/missions');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seePageNavbarAsAdmin();

        $I->see('Upcoming missions');
        $I->see('Completed missions');

        $I->seePageFooter();
    }
}
