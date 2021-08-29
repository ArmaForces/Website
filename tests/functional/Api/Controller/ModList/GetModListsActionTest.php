<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\ModList;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Entity\User\User;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Ramsey\Uuid\Uuid;
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
    public function getModListByNameAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
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

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function getModListByNameAction_nonExistingModList_returnsNotFoundResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::API_MOD_LIST_BY_NAME, Uuid::uuid4()->toString()), [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
        $this::assertJsonContains([
            'detail' => 'Not Found',
        ]);
    }
}
