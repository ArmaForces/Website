<?php

declare(strict_types=1);

namespace App\Attendance\Domain\Model\Attendance;

use App\SharedKernel\Domain\Model\EntityInterface;

interface AttendanceInterface extends EntityInterface
{
    public function getMissionId(): string;

    public function getPlayerId(): int;
}
