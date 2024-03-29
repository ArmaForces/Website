<?php

declare(strict_types=1);

namespace App\Shared\Service\Mission\Enum;

enum MissionStateEnum: string
{
    case ARCHIVED = 'Archived';
    case OPEN = 'Open';
    case CLOSED = 'Closed';
}
