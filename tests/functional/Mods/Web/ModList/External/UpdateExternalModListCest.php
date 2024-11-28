<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\External;

use App\Mods\DataFixtures\ModList\External\GoogleExternalModList;
use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\Entity\ModList\ExternalModList;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User1Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class UpdateExternalModListCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
        $I->freezeTime('2021-01-01T00:00:00+00:00');
    }

    public function updateExternalModListAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseRedirectsToLogInAction();
    }

    public function updateExternalModListAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID);

        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function updateExternalModListAsAuthorizedUser(FunctionalTester $I): void
    {
        $currentUser = $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        // Default form values
        $I->seeInField('Mod list name', 'Google');
        $I->seeInField('Mod list description', '');
        $I->dontSeeCheckboxIsChecked('Mod list active');

        // Fill form
        $I->fillField('Mod list name', 'External');
        $I->fillField('Mod list description', 'External modlist');
        $I->fillField('Mod list url', 'https://external.local');
        $I->checkOption('Mod list active');
        $I->click('Apply');

        $I->seeResponseRedirectsTo('/mod-list/list');

        /** @var ExternalModList $modList */
        $modList = $I->grabEntityFromRepository(ExternalModList::class, ['name' => 'External']);
        $I->assertSame('296cc791-c73f-4978-b377-da1d3aa28cfb', $modList->getId()->toString());
        $I->assertSame('External', $modList->getName());
        $I->assertSame('External modlist', $modList->getDescription());
        $I->assertSame('https://external.local', $modList->getUrl());
        $I->assertSame(true, $modList->isActive());

        $I->assertSame('2020-01-01T00:00:00+00:00', $modList->getCreatedAt()->format(DATE_ATOM));
        $I->assertSame(GoogleExternalModList::CREATED_BY_ID, $modList->getCreatedBy()->getId()->toString());
        $I->assertSame('2021-01-01T00:00:00+00:00', $modList->getLastUpdatedAt()?->format(DATE_ATOM));
        $I->assertSame($currentUser->getId()->toString(), $modList->getLastUpdatedBy()?->getId()->toString());
    }

    public function updateExternalModListAsAuthorizedUserWhenModListAlreadyExists(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', DefaultStandardModListFixture::NAME);
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'Mod list with the same name "Default" already exist.');
    }

    public function updateExternalModListAsAuthorizedUserWithoutRequiredData(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', '');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value should not be blank.');
    }

    public function updateExternalModListAsAuthorizedUserWithDataTooLong(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', str_repeat('a', 256));
        $I->fillField('Mod list description', str_repeat('a', 256));
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('name', 'This value is too long. It should have 255 characters or less.');
        $I->seeFormErrorMessage('description', 'This value is too long. It should have 255 characters or less.');
    }

    public function updateExternalModListAsAuthorizedUserWithDataInvalid(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->fillField('Mod list name', 'External');
        $I->fillField('Mod list url', 'invalid');
        $I->click('Apply');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeFormErrorMessage('url', 'This value is not a valid URL.');
    }

    public function updateExternalModListAsAuthorizedUserWhenModListDoesNotExist(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User1Fixture::ID, function (User $user): void {
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage(sprintf('/external-mod-list/%s/update', 'non existing'));

        $I->seeResponseCodeIs(HttpCode::NOT_FOUND);
    }
}
