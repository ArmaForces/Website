<?php

declare(strict_types=1);

namespace App\Tests\Traits;

use App\Mods\Entity\Dlc\Dlc;
use App\Mods\Entity\Mod\SteamWorkshopMod;
use Symfony\Component\DomCrawler\Crawler;

trait ResponseAssertTrait
{
    public function seeResponseRedirectsTo(string $url): void
    {
        $this->seeResponseCodeIsRedirection();
        $this->seeHttpHeader('Location', $url);
    }

    public function seeResponseRedirectsToLogInAction(): void
    {
        $this->seeResponseRedirectsTo('/security/connect/discord');
    }

    public function seeResponseRedirectsToDiscordOauth(): void
    {
        $this->seeResponseCodeIsRedirection();
        $redirect = $this->grabHttpHeader('Location');
        $this->assertTrue(str_starts_with($redirect, 'https://discord.com/oauth2/authorize'));
    }

    public function seeResponseContainsModListPresetWithMods(
        string $fileName,
        array $expectedDlcs,
        array $expectedMods,
    ): void {
        $extractSteamWorkshopItems = function (Crawler $crawler, string $containerName) {
            $containerSelector = sprintf('[data-type="%s"]', $containerName);
            $containerCrawler = $crawler->filter($containerSelector);

            return array_map(static function (\DOMNode $steamWorkshopItemNode) {
                $steamWorkshopItemNodeCrawler = (new Crawler($steamWorkshopItemNode));

                return [
                    'name' => $steamWorkshopItemNodeCrawler->filter('[data-type="DisplayName"]')->html(),
                    'url' => $steamWorkshopItemNodeCrawler->filter('[data-type="Link"]')->attr('href'),
                ];
            }, iterator_to_array($containerCrawler->getIterator()));
        };

        $this->seeHttpHeader('Content-Disposition', sprintf('attachment; filename="%s"', $fileName));

        $crawler = new Crawler($this->grabResponse());
        $includedDlcs = $extractSteamWorkshopItems($crawler, 'DlcContainer');
        $includedMods = $extractSteamWorkshopItems($crawler, 'ModContainer');

        $expectedDlcs = array_map(static function (Dlc $dlc) {
            return [
                'name' => $dlc->getName(),
                'url' => "https://store.steampowered.com/app/{$dlc->getAppId()}",
            ];
        }, $expectedDlcs);

        $expectedMods = array_map(static function (SteamWorkshopMod $steamWorkshopMod) {
            return [
                'name' => $steamWorkshopMod->getName(),
                'url' => "https://steamcommunity.com/sharedfiles/filedetails/?id={$steamWorkshopMod->getItemId()}",
            ];
        }, $expectedMods);

        $this->assertSame($expectedDlcs, $includedDlcs);
        $this->assertSame($expectedMods, $includedMods);
    }
}
