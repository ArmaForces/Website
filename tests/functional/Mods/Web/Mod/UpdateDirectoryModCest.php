<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\Mod;

use App\Mods\DataFixtures\Mod\Directory\ArmaScriptProfilerModFixture;
use App\Mods\DataFixtures\Mod\Directory\Deprecated\R3ModFixture;
use App\Mods\Entity\Mod\DirectoryMod;
use App\Mods\Entity\Mod\Enum\ModStatusEnum;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class UpdateDirectoryModCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2021-01-01T00:00:00+00:00');
    }

    public function updateDirectoryModAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseRedirectsToLogInAction();
    }

    public function updateDirectoryModAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function updateDirectoryModAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Directory');
        $I->seeInField('Mod directory', '@Arma Script Profiler');
        $I->dontSee('Mod status'); // Not visible
        $I->seeInField('Mod name', 'Arma Script Profiler');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->fillField('Mod directory', '@OCAP');
        $I->fillField('Mod name', 'OCAP');
        $I->fillField('Mod description', 'OCAP - AAR');
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var DirectoryMod $mod */
        $mod = $I->grabEntityFromRepository(DirectoryMod::class, ['directory' => '@OCAP']);
        $I->assertSame(null, $mod->getStatus());
        $I->assertSame('OCAP', $mod->getName());
        $I->assertSame('OCAP - AAR', $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(null, $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $mod->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateDirectoryModAsAuthorizedUserWithChangeModStatusPermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
            $user->getPermissions()->modChangeStatus = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Directory');
        $I->seeInField('Mod directory', '@Arma Script Profiler');
        $I->seeOptionIsSelected('Mod status', '');
        $I->seeInField('Mod name', 'Arma Script Profiler');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->fillField('Mod directory', '@OCAP');
        $I->selectOption('Mod status', 'Deprecated');
        $I->fillField('Mod name', 'OCAP');
        $I->fillField('Mod description', 'OCAP - AAR');
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var DirectoryMod $mod */
        $mod = $I->grabEntityFromRepository(DirectoryMod::class, ['directory' => '@OCAP']);
        $I->assertSame(ModStatusEnum::DEPRECATED, $mod->getStatus());
        $I->assertSame('OCAP', $mod->getName());
        $I->assertSame('OCAP - AAR', $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(null, $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $mod->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateDirectoryModAsAuthorizedUserWhenModAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', R3ModFixture::DIRECTORY);
        $I->fillField('Mod name', 'R3');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('directory', 'Mod associated with directory "@R3" already exist.');
    }

    public function updateDirectoryModAsAuthorizedUserWithInvalidDirectoryName(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '@OC/AP');
        $I->fillField('Mod name', 'OCAP');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('directory', 'Invalid directory name.');
    }

    public function updateDirectoryModAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '');
        $I->fillField('Mod name', '');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('directory', 'This value should not be blank.');
        $I->canSeeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function updateDirectoryModAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', ArmaScriptProfilerModFixture::ID));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '@OCAP');
        $I->fillField('Mod name', str_repeat('a', 256));
        $I->fillField('Mod description', str_repeat('a', 256));
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->canSeeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }

    public function updateDirectoryModAsAuthorizedUserWhenModDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modUpdate = true;
        });

        $I->amOnPage(sprintf('/mod/%s/update', 'd2cca3c6-1c5f-4ea7-afcf-a05317a58467'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
