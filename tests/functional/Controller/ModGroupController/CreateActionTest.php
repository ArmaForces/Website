<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModGroupController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\User\User;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModGroupController
 */
final class CreateActionTest extends WebTestCase
{
    use ServicesTrait;

    /**
     * @test
     */
    public function createAction_anonymousUser_returnsRedirectResponse(): void
    {
        $client = $this::getClient();
        $client->request(Request::METHOD_GET, RouteEnum::MOD_GROUP_CREATE);

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function createAction_unauthorizedUser_returnsForbiddenResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, RegularUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, RouteEnum::MOD_GROUP_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function createAction_authorizedUser_returnsSuccessfulResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, RouteEnum::MOD_GROUP_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
