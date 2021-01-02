<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\HomeController;

use App\Entity\User\User;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\HomeController
 */
final class MissionsActionTest extends WebTestCase
{
    use ServicesTrait;
    use DataProvidersTrait;

    public const ROUTE = '/missions';

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function missionsAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, $this::ROUTE);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }
}
