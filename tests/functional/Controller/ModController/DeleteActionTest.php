<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModController;

use App\Entity\Mod\AbstractMod;
use App\Repository\Mod\ModRepository;
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
 * @covers \App\Controller\ModController
 */
final class DeleteActionTest extends WebTestCase
{
    use DataProvidersTrait;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ModRepository $modRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->modRepository = self::getContainer()->get(ModRepository::class);
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modId): void
    {
        /** @var AbstractMod $subjectMod */
        $subjectMod = $this->modRepository->find($modId);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modId): void
    {
        $user = $this->userRepository->find(RegularUserFixture::ID);
        $subjectMod = $this->modRepository->find($modId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modId): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);
        $subjectMod = $this->modRepository->find($modId);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseRedirects(RouteEnum::MOD_LIST, Response::HTTP_FOUND);

        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     */
    public function deleteAction_authorizedUser_returnsNotFoundResponse(): void
    {
        $user = $this->userRepository->find(AdminUserFixture::ID);

        $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, 'non-existing-id'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
