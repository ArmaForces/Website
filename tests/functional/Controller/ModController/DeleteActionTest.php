<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModController;

use App\DataFixtures\User\AdminUserFixture;
use App\DataFixtures\User\RegularUserFixture;
use App\Entity\Mod\AbstractMod;
use App\Repository\Mod\ModRepository;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\DataProvidersTrait;
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

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_anonymousUser_returnsRedirectResponse(string $modId): void
    {
        $client = self::createClient();

        /** @var AbstractMod $subjectMod */
        $subjectMod = self::getContainer()->get(ModRepository::class)->find($modId);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseRedirects(RouteEnum::SECURITY_CONNECT_DISCORD, Response::HTTP_FOUND);
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_unauthorizedUser_returnsForbiddenResponse(string $modId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(RegularUserFixture::ID);
        $subjectMod = self::getContainer()->get(ModRepository::class)->find($modId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseStatusCodeSame(Response::HTTP_FORBIDDEN);
    }

    /**
     * @test
     * @dataProvider modsDataProvider
     */
    public function deleteAction_authorizedUser_returnsSuccessfulResponse(string $modId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find(AdminUserFixture::ID);
        $subjectMod = self::getContainer()->get(ModRepository::class)->find($modId);

        $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

        $this::assertResponseRedirects(RouteEnum::MOD_LIST, Response::HTTP_FOUND);

        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, $subjectMod->getId()));

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
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_DELETE, 'non-existing-id'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }
}
