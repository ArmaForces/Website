<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller\ModListPublicController;

use App\DataFixtures\Mod\Optional;
use App\DataFixtures\Mod\Optional\AceInteractionMenuExpansionModFixture;
use App\DataFixtures\Mod\Required;
use App\DataFixtures\ModList\DefaultModListFixture;
use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\ModList\ModList;
use App\Entity\User\User;
use App\Test\Enum\RouteEnum;
use App\Test\Traits\AssertsTrait;
use App\Test\Traits\DataProvidersTrait;
use App\Test\Traits\ServicesTrait;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @internal
 * @covers \App\Controller\ModListPublicController
 */
final class DownloadActionTest extends WebTestCase
{
    use ServicesTrait;
    use AssertsTrait;
    use DataProvidersTrait;

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function downloadAction_optionalMods_returnsFileResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        /** @var ModList $subjectModList */
        $subjectModList = $this::getEntityById(ModList::class, DefaultModListFixture::ID);

        /** @var SteamWorkshopMod $optionalMod */
        $optionalMod = $this::getEntityById(SteamWorkshopMod::class, AceInteractionMenuExpansionModFixture::ID);

        $client = $this::authenticateClient($user);
        $crawler = $client->request(Request::METHOD_GET, $this->createModListDownloadUrl($subjectModList->getName(), [
            $optionalMod->getId()->toString() => true,
        ]));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertResponseContainsModListAttachmentHeader($client->getResponse(), $subjectModList);
        $this::assertLauncherPresetContainsMods($crawler, [
            $this::getEntityById(SteamWorkshopMod::class, Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this::getEntityById(SteamWorkshopMod::class, Optional\AceInteractionMenuExpansionModFixture::ID),
            $this::getEntityById(SteamWorkshopMod::class, Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $this::getEntityById(SteamWorkshopMod::class, Required\ArmaForcesModsModFixture::ID),
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function downloadAction_requiredMods_returnsFileResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        /** @var ModList $subjectModList */
        $subjectModList = $this::getEntityById(ModList::class, DefaultModListFixture::ID);

        $client = $this::authenticateClient($user);
        $crawler = $client->request(Request::METHOD_GET, sprintf(RouteEnum::MOD_LIST_PUBLIC_DOWNLOAD, $subjectModList->getName()));

        $this::assertResponseStatusCodeSame(Response::HTTP_OK);
        $this::assertResponseContainsModListAttachmentHeader($client->getResponse(), $subjectModList);
        $this::assertLauncherPresetContainsMods($crawler, [
            $this::getEntityById(SteamWorkshopMod::class, Required\Deprecated\LegacyArmaForcesModsModFixture::ID),
            $this::getEntityById(SteamWorkshopMod::class, Required\Broken\ArmaForcesAceMedicalModFixture::ID),
            $this::getEntityById(SteamWorkshopMod::class, Required\ArmaForcesModsModFixture::ID),
        ]);
    }

    /**
     * @test
     * @dataProvider allUserTypesDataProvider
     */
    public function customizeAction_optionalMods_returnsNotFoundResponse(string $userId): void
    {
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        /** @var SteamWorkshopMod $optionalMod */
        $optionalMod = $this::getEntityById(SteamWorkshopMod::class, AceInteractionMenuExpansionModFixture::ID);

        $client = $this::authenticateClient($user);
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
        /** @var User $user */
        $user = $this::getEntityById(User::class, $userId);

        $client = $this::authenticateClient($user);
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
