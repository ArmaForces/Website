<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\ModManagement\Infrastructure\DataFixtures\ModList\DefaultModListFixture;
use App\ModManagement\Infrastructure\Persistence\ModList\ModListRepository;
use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\SharedKernel\Infrastructure\Test\Traits\DataProvidersTrait;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListPublicController
 */
final class CustomizeActionTest extends WebTestCase
{
    use DataProvidersTrait;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ModListRepository $modListRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->modListRepository = self::getContainer()->get(ModListRepository::class);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);
        $subjectModList = $this->modListRepository->find(DefaultModListFixture::ID);

        !$user ?: $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_CUSTOMIZE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_authorizedUser_returnsNotFoundResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);

        !$user ?: $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_CUSTOMIZE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
