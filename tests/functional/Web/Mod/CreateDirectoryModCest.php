<?php

declare(strict_types=1);

namespace App\Tests\Functional\Web\Mod;

use App\DataFixtures\Mod\Directory\ArmaScriptProfilerModFixture;
use App\DataFixtures\User\User1Fixture;
use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Entity\User\User;
use App\Service\IdentifierFactory\IdentifierFactoryStub;
use App\Tests\FunctionalTester;
use Codeception\Util\HttpCode;
use Ramsey\Uuid\Uuid;

class CreateDirectoryModCest
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

    public function createDirectoryModAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createDirectoryModAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createDirectoryModAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Steam Workshop');
        $I->seeInField('Mod directory', '');
        $I->dontSee('Mod status'); // Not visible
        $I->seeInField('Mod name', '');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '@OCAP');
        $I->fillField('Mod name', 'OCAP');
        $I->fillField('Mod description', 'OCAP - AAR');
        $I->click('Create mod');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var DirectoryMod $mod */
        $mod = $I->grabEntityFromRepository(DirectoryMod::class, ['directory' => '@OCAP']);
        $I->assertSame('805c9fcd-d674-4a27-8f0c-78dbf2484bb2', $mod->getId()->toString());
        $I->assertSame(null, $mod->getStatus());
        $I->assertSame('OCAP', $mod->getName());
        $I->assertSame('OCAP - AAR', $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $mod->getLastUpdatedAt());
        $I->assertSame(null, $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function createDirectoryModAsAuthorizedUserWithChangeModStatusPermission(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
            $user->getPermissions()->modChangeStatus = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeOptionIsSelected('Mod source', 'Steam Workshop');
        $I->seeInField('Mod directory', '');
        $I->seeOptionIsSelected('Mod status', '');
        $I->seeInField('Mod name', '');
        $I->seeInField('Mod description', '');

        // Fill form
        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '@OCAP');
        $I->selectOption('Mod status', 'Deprecated');
        $I->fillField('Mod name', 'OCAP');
        $I->fillField('Mod description', 'OCAP - AAR');
        $I->click('Create mod');

        $I->seeResponseRedirectsTo('/mod/list');

        /** @var DirectoryMod $mod */
        $mod = $I->grabEntityFromRepository(DirectoryMod::class, ['directory' => '@OCAP']);
        $I->assertSame(ModStatusEnum::DEPRECATED, $mod->getStatus());
        $I->assertSame('OCAP', $mod->getName());
        $I->assertSame('OCAP - AAR', $mod->getDescription());

        $I->assertSame('2020-01-01T00:00:00+00:00', $mod->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $mod->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $mod->getLastUpdatedAt());
        $I->assertSame(null, $mod->getLastUpdatedBy()?->getId()->toString());
    }

    public function createDirectoryModAsAuthorizedUserWhenModAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', ArmaScriptProfilerModFixture::DIRECTORY);
        $I->fillField('Mod name', 'Arma Script Profiler');
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('directory', 'Mod associated with directory "@Arma Script Profiler" already exist.');
    }

    public function createDirectoryModAsAuthorizedUserWithInvalidDirectoryName(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '@OC/AP');
        $I->fillField('Mod name', 'OCAP');
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('directory', 'Invalid directory name.');
    }

    public function createDirectoryModAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '');
        $I->fillField('Mod name', '');
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('directory', 'This value should not be blank.');
        $I->canSeeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function createDirectoryModAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->modCreate = true;
        });

        $I->amOnPage('/mod/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->selectOption('Mod source', 'Directory');
        $I->fillField('Mod directory', '@OCAP');
        $I->fillField('Mod name', str_repeat('a', 256));
        $I->fillField('Mod description', str_repeat('a', 256));
        $I->click('Create mod');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->canSeeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->canSeeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }
}
