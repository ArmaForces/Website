<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\UserGroupController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Repository\ModGroup\ModGroupRepository;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\UserGroupController
 */
final class DeleteActionTest extends WebTestCase
{
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modGroupId): void
    {
        $client = self::createClient();

        $subjectModGroup = self::getContainer()->get(ModGroupRepository::class)->find($modGroupId);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modGroupId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);
        $subjectModGroup = self::getContainer()->get(ModGroupRepository::class)->find($modGroupId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modGroupId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);
        $subjectModGroup = self::getContainer()->get(ModGroupRepository::class)->find($modGroupId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseRedirects(RouteEnum::MOD_GROUP_LIST);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
