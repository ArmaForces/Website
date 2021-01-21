<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModGroupController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\ModGroup\ModGroup;
use App\Entity\User\User;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModGroupController
 */
final class DeleteActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    public const ROUTE = '/mod-group/%s/delete';

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modGroupId): void
    {
        /** @var ModGroup $subjectModGroup */
        $subjectModGroup = $this::getEntityById(ModGroup::class, $modGroupId);

        $client = $this::getClient();
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModGroup->getName()));

        $this::assertResponseRedirectsToLoginPage();
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modGroupId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, RegularUserFixture::ID);

        /** @var ModGroup $subjectModGroup */
        $subjectModGroup = $this::getEntityById(ModGroup::class, $modGroupId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModGroup->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modGroupId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, AdminUserFixture::ID);

        /** @var ModGroup $subjectModGroup */
        $subjectModGroup = $this::getEntityById(ModGroup::class, $modGroupId);

        $client = $this::authenticateClient($user);
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModGroup->getName()));

        $this::assertResponseRedirectsToModGroupList();

        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, $subjectModGroup->getName()));

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
        $client->request(Request::METHOD_GET, sprintf($this::ROUTE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
