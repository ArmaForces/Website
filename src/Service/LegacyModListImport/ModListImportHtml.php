<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport;

use App\Service\LegacyModListImport\Dto\ModInfo;
use App\Service\LegacyModListImport\Dto\ModlistInfo;
use Symfony\Component\DomCrawler\Crawler;

class ModListImportHtml
{
    public function importFromDirectoryHtml(string $path): ModlistInfo
    {
        $html = file_get_contents($path);
        $crawler = new Crawler($html);
        $modlistName = $crawler->filter('h1')->text();
        $modlistMods = $crawler->filter('tr[data-type="ModContainer"]');
        $modInfo = array_map(static function (\DOMNode $modContainerNode) {
            $modContainerCrawler = (new Crawler($modContainerNode));
            $name = $modContainerCrawler->filter('[data-type="DisplayName"]')->html();
            $url = $modContainerCrawler->filter('[data-type="Link"]')->attr('href');

            return new ModInfo($name, $url);
        }, iterator_to_array($modlistMods->getIterator()));

        return new ModlistInfo($modlistName, $modInfo);
    }
}
