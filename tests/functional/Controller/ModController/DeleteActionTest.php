<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\Mod\AbstractMod;
use App\Entity\User\User;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModController
 */
final class DeleteActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    public const ROUTE = '/mod/%s/delete';

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modId): void
    {
        /** @var AbstractMod $subjectMod */
        $subjectMod = $this::getEntityById(AbstractMod::class, $modId);

        $client = $this::getClient();
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectMod->getId()));

        $this::assertResponseRedirectsToLoginPage();
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, RegularUserFixture::ID);

        /** @var AbstractMod $subjectMod */
        $subjectMod = $this::getEntityById(AbstractMod::class, $modId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectMod->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        /** @var AbstractMod $subjectMod */
        $subjectMod = $this::getEntityById(AbstractMod::class, $modId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectMod->getId()));

        $this::assertResponseRedirectsToModList();

        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectMod->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, 'non-existing-id'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
