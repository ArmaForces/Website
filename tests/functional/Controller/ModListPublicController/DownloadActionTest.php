<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\ModManagement\Infrastructure\DataFixtures\Mod\Optional;
use App\ModManagement\Infrastructure\DataFixtures\Mod\Optional\AceInteractionMenuExpansionModFixture;
use App\ModManagement\Infrastructure\DataFixtures\Mod\Required;
use App\ModManagement\Infrastructure\DataFixtures\ModList\DefaultModListFixture;
use App\ModManagement\Infrastructure\Persistence\Mod\SteamWorkshopModRepository;
use App\ModManagement\Infrastructure\Persistence\ModList\ModListRepository;
use App\SharedKernel\Infrastructure\Test\Enum\RouteEnum;
use App\SharedKernel\Infrastructure\Test\Traits\AssertsTrait;
use App\SharedKernel\Infrastructure\Test\Traits\DataProvidersTrait;
use App\UserManagement\Infrastructure\Persistence\User\UserRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListPublicController
 */
final class DownloadActionTest extends WebTestCase
{
    use AssertsTrait;
    use DataProvidersTrait;

    private KernelBrowser $client;
    private UserRepository $userRepository;
    private ModListRepository $modListRepository;
    private SteamWorkshopModRepository $steamWorkshopModRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = self::createClient();
        $this->userRepository = self::getContainer()->get(UserRepository::class);
        $this->modListRepository = self::getContainer()->get(ModListRepository::class);
        $this->steamWorkshopModRepository = self::getContainer()->get(SteamWorkshopModRepository::class);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function downloadAction_optionalMods_returnsFileResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);
        $subjectModList = $this->modListRepository->find(DefaultModListFixture::ID);
        $optionalMod = $this->steamWorkshopModRepository->find(AceInteractionMenuExpansionModFixture::ID);

        !$user ?: $this->client->loginUser($user);
        $crawler = $this->client->request(Request::METHOD_GET, $this->createModListDownloadUrl($subjectModList->getName(), [
            $optionalMod->getId()->toString() => true,
        ]));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertResponseContainsModListAttachmentHeader($this->client->getResponse(), $subjectModList);
        $this::assertLauncherPresetContainsMods($crawler, [
            $this->steamWorkshopModRepository->find(Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this->steamWorkshopModRepository->find(Optional\AceInteractionMenuExpansionModFixture::ID),
            $this->steamWorkshopModRepository->find(Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $this->steamWorkshopModRepository->find(Required\ArmaForcesModsModFixture::ID),
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function downloadAction_requiredMods_returnsFileResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);
        $subjectModList = $this->modListRepository->find(DefaultModListFixture::ID);

        !$user ?: $this->client->loginUser($user);
        $crawler = $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertResponseContainsModListAttachmentHeader($this->client->getResponse(), $subjectModList);
        $this::assertLauncherPresetContainsMods($crawler, [
            $this->steamWorkshopModRepository->find(Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this->steamWorkshopModRepository->find(Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $this->steamWorkshopModRepository->find(Required\ArmaForcesModsModFixture::ID),
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_optionalMods_returnsNotFoundResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);
        $optionalMod = $this->steamWorkshopModRepository->find(AceInteractionMenuExpansionModFixture::ID);

        !$user ?: $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, $this->createModListDownloadUrl('non-existing-name', [
            $optionalMod->getId()->toString() => true,
        ]));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_requiredMods_returnsNotFoundResponse(string $userId): void
    {
        $user = $this->userRepository->find($userId);

        !$user ?: $this->client->loginUser($user);
        $this->client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    private function createModListDownloadUrl(string $modListId, array $optionalMods = []): string
    {
        $url = sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, $modListId);
        $optionalModsParameter = $optionalMods ? '/'.json_encode($optionalMods) : '';

        return $url.$optionalModsParameter;
    }
}
