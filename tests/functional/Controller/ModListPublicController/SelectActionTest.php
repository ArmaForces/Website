<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\Entity\User\User;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListPublicController
 */
final class SelectActionTest extends WebTestCase
{
    use ServicesTrait;
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function selectAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, RouteEnum::MOD_LIST_PUBLIC_SELECT);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
