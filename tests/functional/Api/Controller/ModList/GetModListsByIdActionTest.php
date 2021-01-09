<?php

declare(strict_types=1);

namespace App\Tests\Functional\Api\Controller\ModList;

use ApiPlatform\Core\Bridge\Symfony\Bundle\Test\ApiTestCase;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\Entity\ModList\ModList;
use App\Entity\User\User;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
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

    public const ROUTE = '/api/mod-lists/%s';

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function getModListByNameAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        /** @var ModList $mod */
        $mod = $this::getEntityById(ModList::class, DefaultModListFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $mod->getId()), [
            'headers' => [
                'Accept' => 'application/json',
            ],
        ]);

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertJsonContains([
            'id' => 'f3e04dae-18a8-4533-99ea-d6d763ebabcf',
            'name' => 'Default',
            'createdAt' => '2020-01-01T00:00:00+01:00',
            'lastUpdatedAt' => null,
            'mods' => [
                [
                    'id' => '0e4e059c-eef6-42a9-aec3-abdab344ec21',
                    'name' => 'ArmaForces - Mods',
                    'source' => 'steam_workshop',
                    'type' => 'required',
                    'itemId' => 1934142795,
                    'directory' => null,
                    'createdAt' => '2020-01-01T00:00:00+01:00',
                    'lastUpdatedAt' => null,
                ],
            ],
        ]);
    }
}
