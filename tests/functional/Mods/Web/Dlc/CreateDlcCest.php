<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\Dlc;

use App\Mods\Entity\Dlc\Dlc;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryStub;
use App\Shared\Service\SteamApiClient\Helper\SteamHelper;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;
use Ramsey\Uuid\Uuid;

class CreateDlcCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2020-01-01T00:00:00+00:00');

        /** @var IdentifierFactoryStub $identifierFactory */
        $identifierFactory = $I->grabService(IdentifierFactoryStub::class);
        $identifierFactory->setIdentifiers([
            Uuid::fromString('805c9fcd-d674-4a27-8f0c-78dbf2484bb2'),
        ]);
    }

    public function createDlcAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/dlc/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createDlcAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createDlcAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Steam Store URL', '');
        $I->seeInField('DLC directory', '');
        $I->seeInField('DLC name', '');
        $I->seeInField('DLC description', '');

        // Fill form
        $I->fillField('Steam Store URL', 'https://store.steampowered.com/app/1681170/Arma_3_Creator_DLC_Western_Sahara');
        $I->fillField('DLC directory', 'ws');
        $I->fillField('DLC name', 'Western Sahara');
        $I->fillField('DLC description', 'Western Sahara Arma 3 DLC');
        $I->click('Create DLC');

        $I->seeResponseRedirectsTo('/dlc/list');

        /** @var Dlc $dlc */
        $dlc = $I->grabEntityFromRepository(Dlc::class, ['appId' => 1681170]);
        $I->assertSame(1681170, $dlc->getAppId());
        $I->assertSame('ws', $dlc->getDirectory());
        $I->assertSame('Western Sahara', $dlc->getName());
        $I->assertSame('Western Sahara Arma 3 DLC', $dlc->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $dlc->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $dlc->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $dlc->getLastUpdatedAt());
        $I->assertSame(null, $dlc->getLastUpdatedBy()?->getId()->toString());
    }

    public function createDlcAsAuthorizedUserWithNameProvidedBySteamWorkshop(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Steam Store URL', '');
        $I->seeInField('DLC directory', '');
        $I->seeInField('DLC name', '');
        $I->seeInField('DLC description', '');

        // Fill form
        $I->fillField('Steam Store URL', 'https://store.steampowered.com/app/1681170/Arma_3_Creator_DLC_Western_Sahara');
        $I->fillField('DLC directory', 'ws');
        $I->fillField('DLC name', '');
        $I->fillField('DLC description', '');
        $I->click('Create DLC');

        $I->seeResponseRedirectsTo('/dlc/list');

        /** @var Dlc $dlc */
        $dlc = $I->grabEntityFromRepository(Dlc::class, ['appId' => 1681170]);
        $I->assertSame('805c9fcd-d674-4a27-8f0c-78dbf2484bb2', $dlc->getId()->toString());
        $I->assertSame(1681170, $dlc->getAppId());
        $I->assertSame('ws', $dlc->getDirectory());
        $I->assertSame('Arma 3 Creator DLC: Western Sahara', $dlc->getName()); // From Steam Workshop
        $I->assertSame(null, $dlc->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $dlc->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $dlc->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $dlc->getLastUpdatedAt());
        $I->assertSame(null, $dlc->getLastUpdatedBy()?->getId()->toString());
    }

    public function createDlcAsAuthorizedUserWhenDlcAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $url = 'https://store.steampowered.com/app/1227700/Arma_3_Creator_DLC_SOG_Prairie_Fire/';
        $I->fillField('Steam Store URL', $url);
        $I->fillField('DLC directory', 'vn');
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $message = sprintf('DLC associated with url "%s" already exist.', $url);
        $I->seeFormErrorMessage('url', $message);
        $I->seeFormErrorMessage('directory', 'DLC associated with directory "vn" already exist.');
    }

    public function createDlcAsAuthorizedUserWithInvalidDlcUrl(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Store URL', 'https://example.com');
        $I->fillField('DLC directory', 'ws');
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'Invalid Steam Store DLC url.');
    }

    public function createDlcAsAuthorizedUserWhenDlcDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $url = SteamHelper::appIdToAppUrl(9999999);
        $I->fillField('Steam Store URL', $url);
        $I->fillField('DLC directory', 'ws');
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'DLC not found.');
    }

    public function createDlcAsAuthorizedUserWhenUrlIsNotAnArma3Dlc(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $url = SteamHelper::appIdToAppUrl(2138330);
        $I->fillField('Steam Store URL', $url);
        $I->fillField('DLC directory', 'ws');
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'Url is not an Arma 3 DLC.');
    }

    public function createDlcAsAuthorizedUserWithInvalidDirectoryName(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Store URL', 'https://store.steampowered.com/app/1681170/Arma_3_Creator_DLC_Western_Sahara');
        $I->fillField('DLC directory', 'w/s');
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('directory', 'Invalid directory name.');
    }

    public function createDlcAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Store URL', '');
        $I->fillField('DLC directory', '');
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'This value should not be blank.');
        $I->seeFormErrorMessage('directory', 'This value should not be blank.');
    }

    public function createDlcAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->dlcCreate = true;
        });

        $I->amOnPage('/dlc/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Store URL', 'https://store.steampowered.com/app/1681170/Arma_3_Creator_DLC_Western_Sahara');
        $I->fillField('DLC directory', 'ws');
        $I->fillField('DLC name', str_repeat('a', 256));
        $I->fillField('DLC description', str_repeat('a', 256));
        $I->click('Create DLC');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }
}
