<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\UserController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\UserController
 */
final class DeleteActionTest extends WebTestCase
{
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $userId): void
    {
        $client = self::createClient();

        $subjectUser = self::getContainer()->get(UserRepository::class)->find($userId);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_DELETE, $subjectUser->getId()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $userId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);
        $subjectUser = self::getContainer()->get(UserRepository::class)->find($userId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_DELETE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsRedirectResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);
        $subjectUser = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_DELETE, $subjectUser->getId()));

        $this::assertResponseRedirects(RouteEnum::USER_LIST);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_DELETE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedCurrentUser_returnsForbiddenResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);
        $subjectUser = $user;

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_DELETE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_DELETE, 'non-existing-id'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
