<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublic;

use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListPublic\SelectAction
 */
final class SelectActionTest extends WebTestCase
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
     * @dataProvider allUserTypesDataProvider
     */
    public function selectAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);

        !$user ?: $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_PUBLIC_SELECT);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
