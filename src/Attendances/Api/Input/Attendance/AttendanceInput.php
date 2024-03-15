<?php

declare(strict_types=1);

namespace App\Attendances\Api\Input\Attendance;

use App\Attendances\Validator\Attendance\UniqueAttendance;
use App\Shared\Validator\Common\SteamProfileId;
use Symfony\Component\Validator\Constraints as Assert;

#[UniqueAttendance]
class AttendanceInput
{
    #[Assert\NotBlank]
    #[Assert\Length(max: 255)]
    public string $missionId;

    #[Assert\NotBlank]
    #[SteamProfileId]
    public int $playerId;
}
