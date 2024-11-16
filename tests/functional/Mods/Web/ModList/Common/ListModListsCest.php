<?php

declare(strict_types=1);

namespace App\Tests\Functional\Mods\Web\ModList\Common;

use App\Mods\DataFixtures\ModList\External\GoogleExternalModList;
use App\Mods\DataFixtures\ModList\External\LocalhostExternalModList;
use App\Mods\DataFixtures\ModList\Standard\CupStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\DefaultStandardModListFixture;
use App\Mods\DataFixtures\ModList\Standard\RhsStandardModListFixture;
use App\Tests\FunctionalTester;
use App\Users\DataFixtures\User\User3Fixture;
use App\Users\Entity\User\User;
use Codeception\Util\HttpCode;

class ListModListsCest
{
    public function _before(FunctionalTester $I): void
    {
        $I->stopFollowingRedirects();
    }

    public function listModListsAsUnauthenticatedUser(FunctionalTester $I): void
    {
        $I->amOnPage('/mod-list/list');
        $I->seeResponseRedirectsToLogInAction();
    }

    public function listModListsAsUnauthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID);

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::FORBIDDEN);
    }

    public function listModListsAsAuthorizedUser(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithPermissionsToCreateBothTypesOfModList(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->standardModListCreate = true;
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create mod list');
        $I->seeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithCreateStandardModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->standardModListCreate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->seeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithUpdateStandardModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->standardModListUpdate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->seeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', CupStandardModListFixture::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/external-mod-list/%s/update', LocalhostExternalModList::NAME));
        $I->seeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithStandardModListOwnership(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(CupStandardModListFixture::OWNER_ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->seeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', CupStandardModListFixture::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/external-mod-list/%s/update', LocalhostExternalModList::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', DefaultStandardModListFixture::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Copy and edit mod list');

        $I->seeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', CupStandardModListFixture::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/external-mod-list/%s/delete', GoogleExternalModList::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/external-mod-list/%s/delete', LocalhostExternalModList::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', DefaultStandardModListFixture::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', RhsStandardModListFixture::NAME));
    }

    public function listModListsAsAuthorizedUserWithCopyStandardModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->standardModListCopy = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');

        $I->seeActionButton('Copy and edit mod list', sprintf('/standard-mod-list/%s/copy', CupStandardModListFixture::NAME));
        $I->seeActionButton('Copy and edit mod list', sprintf('/standard-mod-list/%s/copy', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Copy and edit mod list', sprintf('/standard-mod-list/%s/copy', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithDeleteStandardModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->standardModListDelete = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');

        $I->seeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', CupStandardModListFixture::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/external-mod-list/%s/delete', GoogleExternalModList::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/external-mod-list/%s/delete', LocalhostExternalModList::NAME));
        $I->seeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', RhsStandardModListFixture::NAME));
    }

    public function listModListsAsAuthorizedUserWithCreateExternalModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->externalModListCreate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->seeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithUpdateExternalModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->externalModListUpdate = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', CupStandardModListFixture::NAME));
        $I->seeActionButton('Edit mod list', sprintf('/external-mod-list/%s/update', GoogleExternalModList::NAME));
        $I->seeActionButton('Edit mod list', sprintf('/external-mod-list/%s/update', LocalhostExternalModList::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', DefaultStandardModListFixture::NAME));
        $I->dontSeeActionButton('Edit mod list', sprintf('/standard-mod-list/%s/update', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Copy and edit mod list');
        $I->dontSeeActionButton('Delete mod list');
    }

    public function listModListsAsAuthorizedUserWithDeleteExternalModListPermission(FunctionalTester $I): void
    {
        $I->amDiscordAuthenticatedAs(User3Fixture::ID, function (User $user): void {
            $user->getPermissions()->modListList = true;
            $user->getPermissions()->externalModListDelete = true;
        });

        $I->amOnPage('/mod-list/list');

        $I->seeResponseCodeIs(HttpCode::OK);

        $I->dontSeeLink('Create mod list');
        $I->dontSeeLink('Create external mod list');

        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', CupStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', GoogleExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', LocalhostExternalModList::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', DefaultStandardModListFixture::NAME));
        $I->seeActionButton('Download mod list', sprintf('/mod-list/%s', RhsStandardModListFixture::NAME));

        $I->dontSeeActionButton('Edit mod list');
        $I->dontSeeActionButton('Copy and edit mod list');

        $I->dontSeeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', CupStandardModListFixture::NAME));
        $I->seeActionButton('Delete mod list', sprintf('/external-mod-list/%s/delete', GoogleExternalModList::NAME));
        $I->seeActionButton('Delete mod list', sprintf('/external-mod-list/%s/delete', LocalhostExternalModList::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', DefaultStandardModListFixture::NAME));
        $I->dontSeeActionButton('Delete mod list', sprintf('/standard-mod-list/%s/delete', RhsStandardModListFixture::NAME));
    }
}
