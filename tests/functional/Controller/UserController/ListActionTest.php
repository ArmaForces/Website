<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\UserController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\User\User;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\UserController
 */
final class ListActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;

    public const ROUTE = '/user/list';

    /**
     * @test
     */
    public function listAction_anonymousUser_returnsRedirectResponse(): void
    {
        $client = $this::getClient();
        $client->request(Request::METHOD_GET, $this::ROUTE);

        $this::assertResponseRedirectsToLoginPage();
    }

    /**
     * @test
     */
    public function listAction_unauthorizedUser_returnsForbiddenResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, RegularUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, $this::ROUTE);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function listAction_authorizedUser_returnsSuccessfulResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, $this::ROUTE);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
