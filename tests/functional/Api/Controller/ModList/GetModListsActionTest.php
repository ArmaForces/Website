<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\ModList;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User\User;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class GetModListsActionTest extends ApiTestCase
{
    use ServicesTrait;
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function getModListsAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, RouteEnum::API_MOD_LIST_LIST, [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertJsonContains([
            [
                'id' => 'f3e04dae-18a8-4533-99ea-d6d763ebabcf',
                'name' => 'Default',
                'createdAt' => '2020-01-01T00:00:00+01:00',
                'lastUpdatedAt' => null,
            ],
        ]);
    }
}
