<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\ModList\ModList;
use App\Entity\User\User;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListController
 */
final class DeleteActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    public const ROUTE = '/mod-list/%s/delete';

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modListId): void
    {
        /** @var ModList $subjectModList */
        $subjectModList = $this::getEntityById(ModList::class, $modListId);

        $client = $this::getClient();
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModList->getName()));

        $this::assertResponseRedirectsToLoginPage();
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modListId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, RegularUserFixture::ID);

        /** @var ModList $subjectModList */
        $subjectModList = $this::getEntityById(ModList::class, $modListId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modListId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        /** @var ModList $subjectModList */
        $subjectModList = $this::getEntityById(ModList::class, $modListId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModList->getName()));

        $this::assertResponseRedirectsToModListList();

        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
