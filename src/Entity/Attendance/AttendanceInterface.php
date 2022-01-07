<?php

declare(strict_types=1);

namespace App\Entity\Attendance;

use App\SharedKernel\Domain\Model\EntityInterface;

interface AttendanceInterface extends EntityInterface
{
    public function getMissionId(): string;

    public function getPlayerId(): int;
}
