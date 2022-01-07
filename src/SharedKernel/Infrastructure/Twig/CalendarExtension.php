<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Twig;

use App\SharedKernel\Infrastructure\Service\Mission\Dto\MissionDto;
use Spatie\CalendarLinks\Link;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class CalendarExtension extends AbstractExtension
{
    public function getFunctions(): array
    {
        return [
            new TwigFunction('mission_event_google', [$this, 'createMissionGoogleLink']),
            new TwigFunction('mission_event_ical', [$this, 'createMissionIcalLink']),
        ];
    }

    public function createMissionGoogleLink(MissionDto $mission): string
    {
        return $this->createMissionEventLink($mission)->google();
    }

    public function createMissionIcalLink(MissionDto $mission): string
    {
        return $this->createMissionEventLink($mission)->ics();
    }

    protected function createMissionEventLink(MissionDto $mission): Link
    {
        $fromDate = \DateTime::createFromImmutable($mission->getDate())->sub(new \DateInterval('PT30M'));
        $toDate = \DateTime::createFromImmutable($mission->getDate())->add(new \DateInterval('PT2H'));

        return Link::create(sprintf('[AF] %s', $mission->getTitle()), $fromDate, $toDate);
    }
}
