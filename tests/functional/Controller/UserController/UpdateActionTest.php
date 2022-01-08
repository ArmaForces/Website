<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\UserController;

use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\SharedKernel\Infrastructure\Test\Traits\DataProvidersTrait;
use App\UserManagement\Infrastructure\DataFixtures\User\AdminUserFixture;
use App\UserManagement\Infrastructure\DataFixtures\User\RegularUserFixture;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\UserManagement\UserInterface\Http\Controller\UserController
 */
final class UpdateActionTest extends WebTestCase
{
    use DataProvidersTrait;

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
     * @dataProvider registeredUsersDataProvider
     */
    public function updateAction_anonymousUser_returnsRedirectResponse(string $userId): void
    {
        $subjectUser = $this->userRepository->find($userId);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, $subjectUser->getId()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function updateAction_unauthorizedUser_returnsForbiddenResponse(string $userId): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);
        $subjectUser = $this->userRepository->find($userId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function updateAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);
        $subjectUser = $this->userRepository->find($userId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @test
     */
    public function updateAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::USER_UPDATE, 'non-existing-id'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
