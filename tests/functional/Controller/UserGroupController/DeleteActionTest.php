<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\UserGroupController;

use App\Repository\ModGroup\ModGroupRepository;
use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\SharedKernel\Infrastructure\Test\Traits\DataProvidersTrait;
use App\UserManagement\Infrastructure\DataFixtures\User\AdminUserFixture;
use App\UserManagement\Infrastructure\DataFixtures\User\RegularUserFixture;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\UserManagement\UserInterface\Http\Controller\UserGroupController
 */
final class DeleteActionTest extends WebTestCase
{
    use DataProvidersTrait;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ModGroupRepository $modGroupRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->modGroupRepository = self::getContainer()->get(ModGroupRepository::class);
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modGroupId): void
    {
        $subjectModGroup = $this->modGroupRepository->find($modGroupId);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modGroupId): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);
        $subjectModGroup = $this->modGroupRepository->find($modGroupId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modGroupsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modGroupId): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);
        $subjectModGroup = $this->modGroupRepository->find($modGroupId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseRedirects(RouteEnum::MOD_GROUP_LIST);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, $subjectModGroup->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_GROUP_DELETE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
