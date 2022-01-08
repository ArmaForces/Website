<?php

declare(strict_types=1);

namespace App\Attendance\Infrastructure\DataFixtures\Attendance;

use App\Attendance\Domain\Model\Attendance\Attendance;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AttendanceFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        for ($i = 1; $i <= 10; ++$i) {
            $attendance = new Attendance(Uuid::uuid4(), "mission_{$i}", 76561198048200529);
            $manager->persist($attendance);
        }

        $manager->flush();
    }
}
