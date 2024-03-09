<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\Mod;

use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\Mods\Entity\Mod\AbstractMod;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class DeleteModCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function deleteModAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod/%s/delete', ArmaForcesMedicalModFixture::ID));

        $I->seeResponseRedirectsToLogInAction();

        $I->seeInRepository(AbstractMod::class, ['id' => ArmaForcesMedicalModFixture::ID]);
    }

    public function deleteModAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod/%s/delete', ArmaForcesMedicalModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);

        $I->seeInRepository(AbstractMod::class, ['id' => ArmaForcesMedicalModFixture::ID]);
    }

    public function deleteModAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modDelete = true;
        });

        $I->amOnPage(sprintf('/mod/%s/delete', ArmaForcesMedicalModFixture::ID));

        $I->seeResponseRedirectsTo('/mod/list');

        $I->dontSeeInRepository(AbstractMod::class, ['id' => ArmaForcesMedicalModFixture::ID]);
    }

    public function deleteModAsAuthorizedUserWhenModDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modDelete = true;
        });

        $I->amOnPage(sprintf('/mod/%s/delete', 'd2cca3c6-1c5f-4ea7-afcf-a05317a58467'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
