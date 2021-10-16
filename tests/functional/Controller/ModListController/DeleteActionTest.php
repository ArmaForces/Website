<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Repository\ModList\ModListRepository;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListController
 */
final class DeleteActionTest extends WebTestCase
{
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modListId): void
    {
        $client = self::createClient();

        $subjectModList = self::getContainer()->get(ModListRepository::class)->find($modListId);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modListId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);
        $subjectModList = self::getContainer()->get(ModListRepository::class)->find($modListId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modListId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);
        $subjectModList = self::getContainer()->get(ModListRepository::class)->find($modListId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseRedirects(RouteEnum::MOD_LIST_LIST);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
