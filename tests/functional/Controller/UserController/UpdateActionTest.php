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
final class UpdateActionTest extends WebTestCase
{
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function updateAction_anonymousUser_returnsRedirectResponse(string $userId): void
    {
        $client = self::createClient();

        $subjectUser = self::getContainer()->get(UserRepository::class)->find($userId);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, $subjectUser->getId()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function updateAction_unauthorizedUser_returnsForbiddenResponse(string $userId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);
        $subjectUser = self::getContainer()->get(UserRepository::class)->find($userId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function updateAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);
        $subjectUser = self::getContainer()->get(UserRepository::class)->find($userId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function updateAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, 'non-existing-id'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
