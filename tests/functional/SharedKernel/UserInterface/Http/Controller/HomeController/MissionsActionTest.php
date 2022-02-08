<?php

declare(strict_types=1);

namespace App\Tests\Functional\SharedKernel\UserInterface\Http\Controller\HomeController;

use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\SharedKernel\Infrastructure\Test\Traits\AssertsTrait;
use App\SharedKernel\Infrastructure\Test\Traits\DataProvidersTrait;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\SharedKernel\UserInterface\Http\Controller\HomeController
 */
final class MissionsActionTest extends WebTestCase
{
    use AssertsTrait;
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
     */
    public function missionsUsAction_anonymousUser_returnsSuccessfulResponse(): void
    {
        $crawler = $this->client->request(Request::METHOD_GET, RouteEnum::HOME_MISSIONS);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertTeamSpeakUrlVisible($crawler, false);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function missionsUsAction_authenticatedUser_returnsSuccessfulResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);

        $this->client->loginUser($user);
        $crawler = $this->client->request(Request::METHOD_GET, RouteEnum::HOME_MISSIONS);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertTeamSpeakUrlVisible($crawler, true);
    }
}
