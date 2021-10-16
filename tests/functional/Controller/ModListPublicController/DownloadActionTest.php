<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\DataFixtures\Mod\Optional;
use App\DataFixtures\Mod\Optional\AceInteractionMenuExpansionModFixture;
use App\DataFixtures\Mod\Required;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\Repository\Mod\SteamWorkshopModRepository;
use App\Repository\ModList\ModListRepository;
use App\Repository\User\UserRepository;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
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

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function downloadAction_optionalMods_returnsFileResponse(string $userId): void
    {
        $client = self::createClient();

        $steamWorkshopModRepository = self::getContainer()->get(SteamWorkshopModRepository::class);

        $user = self::getContainer()->get(UserRepository::class)->find($userId);
        $subjectModList = self::getContainer()->get(ModListRepository::class)->find(DefaultModListFixture::ID);
        $optionalMod = $steamWorkshopModRepository->find(AceInteractionMenuExpansionModFixture::ID);

        !$user ?: $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET, $this->createModListDownloadUrl($subjectModList->getName(), [
            $optionalMod->getId()->toString() => true,
        ]));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertResponseContainsModListAttachmentHeader($client->getResponse(), $subjectModList);
        $this::assertLauncherPresetContainsMods($crawler, [
            $steamWorkshopModRepository->find(Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $steamWorkshopModRepository->find(Optional\AceInteractionMenuExpansionModFixture::ID),
            $steamWorkshopModRepository->find(Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $steamWorkshopModRepository->find(Required\ArmaForcesModsModFixture::ID),
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function downloadAction_requiredMods_returnsFileResponse(string $userId): void
    {
        $client = self::createClient();

        $steamWorkshopModRepository = self::getContainer()->get(SteamWorkshopModRepository::class);

        $user = self::getContainer()->get(UserRepository::class)->find($userId);
        $subjectModList = self::getContainer()->get(ModListRepository::class)->find(DefaultModListFixture::ID);

        !$user ?: $client->loginUser($user);
        $crawler = $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertResponseContainsModListAttachmentHeader($client->getResponse(), $subjectModList);
        $this::assertLauncherPresetContainsMods($crawler, [
            $steamWorkshopModRepository->find(Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $steamWorkshopModRepository->find(Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $steamWorkshopModRepository->find(Required\ArmaForcesModsModFixture::ID),
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_optionalMods_returnsNotFoundResponse(string $userId): void
    {
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find($userId);
        $optionalMod = self::getContainer()->get(SteamWorkshopModRepository::class)->find(AceInteractionMenuExpansionModFixture::ID);

        !$user ?: $client->loginUser($user);
        $client->request(Request::METHOD_GET, $this->createModListDownloadUrl('non-existing-name', [
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
        $client = self::createClient();

        $user = self::getContainer()->get(UserRepository::class)->find($userId);

        !$user ?: $client->loginUser($user);
        $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, 'non-existing-name'));

        $this::assertResponseStatusCodeSame(Response::HTTP_NOT_FOUND);
    }

    private function createModListDownloadUrl(string $modListId, array $optionalMods = []): string
    {
        $url = sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, $modListId);
        $optionalModsParameter = $optionalMods ? '/'.json_encode($optionalMods) : '';

        return $url.$optionalModsParameter;
    }
}
