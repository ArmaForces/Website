<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\Mod;

use App\DataFixtures\Mod\SteamWorkshop\Required\ArmaForcesMedicalModFixture;
use App\DataFixtures\User\User1Fixture;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\Mod\Enum\ModTypeEnum;
use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\User\User;
use App\Service\SteamApiClient\Helper\SteamHelper;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;

class CreateSteamWorkshopModCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2020-01-01T00:00:00+00:00');
    }

    public function createSteamWorkshopModAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createSteamWorkshopModAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createSteamWorkshopModAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Steam Workshop');
        $I->seeInField('Steam Workshop URL', '');
        $I->seeOptionIsSelected('Mod type', 'Required mod');
        $I->dontSee('Mod status'); // Not visible
        $I->seeInField('Mod name', '');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->selectOption('Mod source', 'Steam Workshop');
        $I->fillField('Steam Workshop URL', 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795');
        $I->selectOption('Mod type', 'Optional mod');
        $I->fillField('Mod name', 'AF Mods');
        $I->fillField('Mod description', 'Custom Arma 3 mods');
        $I->click('Create mod');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var SteamWorkshopMod $mod */
        $mod = $I->grabEntityFromRepository(SteamWorkshopMod::class, ['itemId' => 1934142795]);
        $I->assertSame(ModTypeEnum::OPTIONAL, $mod->getType());
        $I->assertSame(null, $mod->getStatus());
        $I->assertSame('AF Mods', $mod->getName());
        $I->assertSame('Custom Arma 3 mods', $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $mod->getLastUpdatedAt());
        $I->assertSame(null, $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function createSteamWorkshopModAsAuthorizedUserWithNameProvidedBySteamWorkshop(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Steam Workshop');
        $I->seeInField('Steam Workshop URL', '');
        $I->seeOptionIsSelected('Mod type', 'Required mod');
        $I->dontSee('Mod status'); // Not visible
        $I->seeInField('Mod name', '');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->selectOption('Mod source', 'Steam Workshop');
        $I->fillField('Steam Workshop URL', 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795');
        $I->selectOption('Mod type', 'Required mod');
        $I->fillField('Mod name', '');
        $I->fillField('Mod description', '');
        $I->click('Create mod');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var SteamWorkshopMod $mod */
        $mod = $I->grabEntityFromRepository(SteamWorkshopMod::class, ['itemId' => 1934142795]);
        $I->assertSame(ModTypeEnum::REQUIRED, $mod->getType());
        $I->assertSame(null, $mod->getStatus());
        $I->assertSame('ArmaForces - Mods', $mod->getName()); // From Steam Workshop
        $I->assertSame(null, $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $mod->getLastUpdatedAt());
        $I->assertSame(null, $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function createSteamWorkshopModAsAuthorizedUserWithChangeModStatusPermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
            $user->getPermissions()->modChangeStatus = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Steam Workshop');
        $I->seeInField('Steam Workshop URL', '');
        $I->seeOptionIsSelected('Mod type', 'Required mod');
        $I->seeOptionIsSelected('Mod status', '');
        $I->seeInField('Mod name', '');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->selectOption('Mod source', 'Steam Workshop');
        $I->fillField('Steam Workshop URL', 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795');
        $I->selectOption('Mod type', 'Required mod');
        $I->selectOption('Mod status', 'Deprecated');
        $I->fillField('Mod name', 'AF Mods');
        $I->fillField('Mod description', 'Custom Arma 3 mods');
        $I->click('Create mod');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var SteamWorkshopMod $mod */
        $mod = $I->grabEntityFromRepository(SteamWorkshopMod::class, ['itemId' => 1934142795]);
        $I->assertSame(ModTypeEnum::REQUIRED, $mod->getType());
        $I->assertSame(ModStatusEnum::DEPRECATED, $mod->getStatus());
        $I->assertSame('AF Mods', $mod->getName());
        $I->assertSame('Custom Arma 3 mods', $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $mod->getLastUpdatedAt());
        $I->assertSame(null, $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function createSteamWorkshopModAsAuthorizedUserWhenModAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $url = SteamHelper::itemIdToItemUrl(ArmaForcesMedicalModFixture::ITEM_ID);
        $I->fillField('Steam Workshop URL', $url);
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $message = sprintf('Mod associated with url "%s" already exist.', $url);
        $I->seeFormErrorMessage('url', $message);
    }

    public function createSteamWorkshopModAsAuthorizedUserWithInvalidModUrl(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Workshop URL', 'https://example.com');
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'Invalid Steam Workshop mod url.');
    }

    public function createSteamWorkshopModAsAuthorizedUserWhenModDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $url = SteamHelper::itemIdToItemUrl(9999999);
        $I->fillField('Steam Workshop URL', $url);
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'Mod not found.');
    }

    public function createSteamWorkshopModAsAuthorizedUserWhenUrlIsNotAnArma3Mod(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $url = SteamHelper::itemIdToItemUrl(455312245);
        $I->fillField('Steam Workshop URL', $url);
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'Url is not an Arma 3 mod.');
    }

    public function createSteamWorkshopModAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Workshop URL', '');
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'This value should not be blank.');
    }

    public function createSteamWorkshopModAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Steam Workshop URL', 'https://steamcommunity.com/sharedfiles/filedetails/?id=1934142795');
        $I->fillField('Mod name', str_repeat('a', 256));
        $I->fillField('Mod description', str_repeat('a', 256));
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }
}
