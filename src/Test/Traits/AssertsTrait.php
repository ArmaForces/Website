<?php

declare(strict_types=1);

namespace App\Test\Traits;

use Symfony\Component\DomCrawler\Crawler;

trait AssertsTrait
{
    public static function assertTeamSpeakUrlVisible(Crawler $crawler, bool $visible): void
    {
        $expectedUrl = $visible ? 'teamspeak.example.com' : null;

        $url = $crawler->filter('.icon-teamspeak a')->attr('href');
        self::assertSame($expectedUrl, $url);
    }
}
