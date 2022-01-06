<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListController
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
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_CREATE);

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function createAction_unauthorizedUser_returnsForbiddenResponse(): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function createAction_authorizedUser_returnsSuccessfulResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_CREATE);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
