<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\External;

use App\Mods\DataFixtures\ModList\External\GoogleExternalModList;
use App\Mods\Entity\ModList\ExternalModList;
use App\Shared\Service\IdentifierFactory\IdentifierFactoryStub;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;
use Ramsey\Uuid\Uuid;

class CreateExternalModListCest
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

    public function createExternalModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseRedirectsToLogInAction();
    }

    public function createExternalModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function createExternalModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', '');
        $I->seeInField('Mod list description', '');
        $I->seeInField('Mod list url', '');
        $I->seeCheckboxIsChecked('Mod list active');

        // Fill form
        $I->fillField('Mod list name', 'External');
        $I->fillField('Mod list description', 'External modlist');
        $I->fillField('Mod list url', 'https://external.local');
        $I->uncheckOption('Mod list active');
        $I->click('Create mod list');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var ExternalModList $modList */
        $modList = $I->grabEntityFromRepository(ExternalModList::class, ['name' => 'External']);
        $I->assertSame('805c9fcd-d674-4a27-8f0c-78dbf2484bb2', $modList->getId()->toString());
        $I->assertSame('External', $modList->getName());
        $I->assertSame('External modlist', $modList->getDescription());
        $I->assertSame('https://external.local', $modList->getUrl());
        $I->assertSame(false, $modList->isActive());

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getCreatedBy()?->getId()->toString());
        $I->assertSame(null, $modList->getLastUpdatedAt());
        $I->assertSame(null, $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function createExternalModListAsAuthorizedUserWhenModListAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', GoogleExternalModList::NAME);
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'Mod list with the same name "Google" already exist.');
    }

    public function createExternalModListAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', '');
        $I->fillField('Mod list url', '');
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
        $I->seeFormErrorMessage('url', 'This value should not be blank.');
    }

    public function createExternalModListAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', str_repeat('a', 256));
        $I->fillField('Mod list description', str_repeat('a', 256));
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }

    public function createExternalModListAsAuthorizedUserWithDataInvalid(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/external-mod-list/create');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', 'External');
        $I->fillField('Mod list url', 'invalid');
        $I->click('Create mod list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'This value is not a valid URL.');
    }
}
