<?php

declare(strict_types=1);

namespace App\Test\Traits;

use App\Entity\Mod\SteamWorkshopMod;
use App\Entity\ModList\ModListInterface;
use Symfony\Component\DomCrawler\Crawler;
use Symfony\Component\HttpFoundation\Response;

trait AssertsTrait
{
    public static function assertTeamSpeakUrlVisible(Crawler $crawler, bool $visible): void
    {
        $expectedUrl = $visible ? 'teamspeak.example.com' : null;

        $url = $crawler->filter('.icon-teamspeak a')->attr('href');
        self::assertSame($expectedUrl, $url);
    }

    public static function assertResponseContainsModListAttachmentHeader(Response $response, ModListInterface $modList): void
    {
        $contentDispositionHeader = $response->headers->get('Content-Disposition');
        $pattern = "/^attachment; filename=\"ArmaForces {$modList->getName()} \\d{4}_\\d{2}_\\d{2} \\d{2}_\\d{2}.html\"$/";

        self::assertTrue(1 === preg_match($pattern, $contentDispositionHeader));
    }

    public static function assertLauncherPresetContainsMods(Crawler $crawler, array $expectedMods): void
    {
        $expectedMods = array_map(static function (SteamWorkshopMod $steamWorkshopMod) {
            return [
                'name' => $steamWorkshopMod->getName(),
                'url' => "https://steamcommunity.com/sharedfiles/filedetails/?id={$steamWorkshopMod->getItemId()}",
            ];
        }, $expectedMods);

        $modContainerNodes = $crawler->filter('[data-type="ModContainer"]');
        $presetMods = array_map(static function (\DOMNode $modContainerNode) {
            $modContainerCrawler = (new Crawler($modContainerNode));

            return [
                'name' => $modContainerCrawler->filter('[data-type="DisplayName"]')->html(),
                'url' => $modContainerCrawler->filter('[data-type="Link"]')->attr('href'),
            ];
        }, iterator_to_array($modContainerNodes->getIterator()));

        self::assertSame(var_export($expectedMods, true), var_export($presetMods, true));
    }
}
