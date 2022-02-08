<?php

declare(strict_types=1);

namespace App\Tests\Functional\UserManagement\UserInterface\Http\Controller\UserGroupController;

use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\UserManagement\Infrastructure\DataFixtures\User\AdminUserFixture;
use App\UserManagement\Infrastructure\DataFixtures\User\RegularUserFixture;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\UserManagement\UserInterface\Http\Controller\UserGroupController
 */
final class CreateActionTest extends WebTestCase
{
    private KernelBrowser $client;
    private UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
    }

    /**
     * @test
     */
    public function createAction_anonymousUser_returnsRedirectResponse(): void
    {
        $this->client->request(Request::METHOD_GET, RouteEnum::USER_GROUP_CREATE);

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function createAction_unauthorizedUser_returnsForbiddenResponse(): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::USER_GROUP_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function createAction_authorizedUser_returnsSuccessfulResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::USER_GROUP_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
