<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\HomeController;

use App\Entity\User\User;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\HomeController
 */
final class IndexActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    /**
     * @test
     */
    public function indexAction_anonymousUser_returnsSuccessfulResponse(): void
    {
        $client = $this::createClient();
        $crawler = $client->request(Request::METHOD_GET, RouteEnum::HOME_INDEX);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertTeamSpeakUrlVisible($crawler, false);
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function indexAction_authenticatedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $crawler = $client->request(Request::METHOD_GET, RouteEnum::HOME_INDEX);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertTeamSpeakUrlVisible($crawler, true);
    }
}
