<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\ModList;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @coversNothing
 */
final class GetModListsActionTest extends ApiTestCase
{
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function getModListsAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        $client = self::createClient();
        $user = self::getContainer()->get(UserRepository::class)->find($userId);

        !$user ?: $client->getKernelBrowser()->loginUser($user);
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
                'active' => true,
                'approved' => false,
                'createdAt' => '2020-01-01T00:00:00+01:00',
                'lastUpdatedAt' => null,
            ],
        ]);
    }
}
