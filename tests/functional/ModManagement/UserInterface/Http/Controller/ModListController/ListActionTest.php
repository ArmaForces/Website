<?php

declare(strict_types=1);

namespace App\Tests\Functional\ModManagement\UserInterface\Http\Controller\ModListController;

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
 * @covers \App\ModManagement\UserInterface\Http\Controller\ModListController
 */
final class ListActionTest extends WebTestCase
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
    public function listAction_anonymousUser_returnsRedirectResponse(): void
    {
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_LIST);

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     */
    public function listAction_unauthorizedUser_returnsForbiddenResponse(): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_LIST);

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function listAction_authorizedUser_returnsSuccessfulResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_LIST);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
