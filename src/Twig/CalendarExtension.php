<?php

declare(strict_types=1);

namespace App\Twig;

use App\Service\Mission\Dto\MissionDto;
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
        $eventDate = \DateTime::createFromImmutable($mission->getCloseDate());

        return Link::create($mission->getTitle(), $eventDate, $eventDate);
    }
}
