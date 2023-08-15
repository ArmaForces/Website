<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModList;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Repository\ModList\ModListRepository;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModList\DeleteAction
 */
final class DeleteActionTest extends WebTestCase
{
    use DataProvidersTrait;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ModListRepository $modListRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->modListRepository = self::getContainer()->get(ModListRepository::class);
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modListId): void
    {
        $subjectModList = $this->modListRepository->find($modListId);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modListId): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);
        $subjectModList = $this->modListRepository->find($modListId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modListsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modListId): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);
        $subjectModList = $this->modListRepository->find($modListId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseRedirects(RouteEnum::MOD_LIST_LIST);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_DELETE, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
