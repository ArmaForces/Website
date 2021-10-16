<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\DataFixtures\ModList\DefaultModListFixture;
use App\Repository\ModList\ModListRepository;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
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

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find($userId);
        $subjectModList = self::getContainer()->get(ModListRepository::class)->find(DefaultModListFixture::ID);

        !$user ?: $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_CUSTOMIZE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_authorizedUser_returnsNotFoundResponse(string $userId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find($userId);

        !$user ?: $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_CUSTOMIZE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
