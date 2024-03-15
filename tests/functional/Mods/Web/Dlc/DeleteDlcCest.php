<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\Dlc;

use App\Mods\DataFixtures\Dlc\SogPrairieFireDlcFixture;
use App\Mods\Entity\Dlc\Dlc;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class DeleteDlcCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteDlcAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/dlc/%s/delete', SogPrairieFireDlcFixture::ID));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(Dlc::class, ['id' => SogPrairieFireDlcFixture::ID]);
    }

    public function deleteDlcAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/dlc/%s/delete', SogPrairieFireDlcFixture::ID));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(Dlc::class, ['id' => SogPrairieFireDlcFixture::ID]);
    }

    public function deleteDlcAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcDelete = true;
        });

        $I->amOnPage(sprintf('/dlc/%s/delete', SogPrairieFireDlcFixture::ID));

        $I->seeResponseRedirectsTo('/dlc/list');

        $I->dontSeeInRepository(Dlc::class, ['id' => SogPrairieFireDlcFixture::ID]);
    }

    public function deleteDlcAsAuthorizedUserWhenDlcDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcDelete = true;
        });

        $I->amOnPage(sprintf('/dlc/%s/delete', 'd2cca3c6-1c5f-4ea7-afcf-a05317a58467'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
