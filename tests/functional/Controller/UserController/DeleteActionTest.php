<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\UserController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\User\User;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\UserController
 */
final class DeleteActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    public const ROUTE = '/user/%s/delete';

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $userId): void
    {
        /** @var User $subjectUser */
        $subjectUser = $this::getEntityById(User::class, $userId);

        $client = $this::getClient();
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectUser->getId()));

        $this::assertResponseRedirectsToLoginPage();
    }

    /**
     * @test
     * @dataProvider registeredUsersDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, RegularUserFixture::ID);

        /** @var User $subjectUser */
        $subjectUser = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsRedirectResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        /** @var User $subjectUser */
        $subjectUser = $this::getEntityById(User::class, RegularUserFixture::ID);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectUser->getId()));

        $this::assertResponseRedirectsToUserList();

        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedCurrentUser_returnsForbiddenResponse(): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);
        $subjectUser = $user;

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectUser->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
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