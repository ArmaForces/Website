<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\DataFixtures\ModList\DefaultModListFixture;
use App\Entity\ModList\ModList;
use App\Entity\User\User;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListPublicController
 */
final class CustomizeActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    public const ROUTE = '/mod-list/%s';

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_authorizedUser_returnsSuccessfulResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        /** @var ModList $subjectModList */
        $subjectModList = $this::getEntityById(ModList::class, DefaultModListFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_authorizedUser_returnsNotFoundResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
