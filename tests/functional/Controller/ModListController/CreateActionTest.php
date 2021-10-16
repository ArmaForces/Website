<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListController
 */
final class CreateActionTest extends WebTestCase
{
    /**
     * @test
     */
    public function createAction_anonymousUser_returnsRedirectResponse(): void
    {
        $client = self::createClient();
        $client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_CREATE);

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function createAction_unauthorizedUser_returnsForbiddenResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function createAction_authorizedUser_returnsSuccessfulResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
