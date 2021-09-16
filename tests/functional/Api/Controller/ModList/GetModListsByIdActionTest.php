<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\ModList;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\Entity\ModList\ModList;
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
final class GetModListsByIdActionTest extends ApiTestCase
{
    use ServicesTrait;
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function getModListByIdAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        /** @var ModList $mod */
        $mod = $this::getEntityById(ModList::class, DefaultModListFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::API_MOD_LIST_GET_BY_ID, $mod->getId()), [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertJsonContains([
            'id' => 'f3e04dae-18a8-4533-99ea-d6d763ebabcf',
            'name' => 'Default',
            'active' => true,
            'approved' => false,
            'createdAt' => '2020-01-01T00:00:00+01:00',
            'lastUpdatedAt' => null,
            'mods' => [
                [
                    'id' => '7e11c37e-930e-49e8-a87d-8f942d98edb0',
                    'name' => '[legacy] ArmaForces - Mods',
                    'createdAt' => '2020-01-01T00:00:00+01:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => 'deprecated',
                    'type' => 'required',
                    'itemId' => 1639399387,
                    'directory' => null,
                ],
                [
                    'id' => 'b8e88103-69d2-438b-8d89-933ccfdb3a5a',
                    'name' => '[OBSOLETE] ArmaForces - JBAD Building Fix',
                    'createdAt' => '2020-01-01T00:00:00+01:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => 'disabled',
                    'type' => 'required',
                    'itemId' => 1781106281,
                    'directory' => null,
                ],
                [
                    'id' => '37f58e30-5194-4594-89af-4a82c7fc02be',
                    'name' => 'ACE Interaction Menu Expansion',
                    'createdAt' => '2020-01-01T00:00:00+01:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'optional',
                    'itemId' => 1376867375,
                    'directory' => null,
                ],
                [
                    'id' => '2f1d2dea-a7a6-4509-b478-66a980d724ca',
                    'name' => 'ArmaForces - ACE Medical [OBSOLETE]',
                    'createdAt' => '2020-01-01T00:00:00+01:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => 'broken',
                    'type' => 'required',
                    'itemId' => 1704054308,
                    'directory' => null,
                ],
                [
                    'id' => '0e4e059c-eef6-42a9-aec3-abdab344ec21',
                    'name' => 'ArmaForces - Mods',
                    'createdAt' => '2020-01-01T00:00:00+01:00',
                    'lastUpdatedAt' => null,
                    'source' => 'steam_workshop',
                    'status' => null,
                    'type' => 'required',
                    'itemId' => 1934142795,
                    'directory' => null,
                ],
            ],
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function getModListByIdAction_nonExistingModList_returnsNotFoundResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::API_MOD_LIST_GET_BY_ID, Uuid::uuid4()->toString()), [
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
