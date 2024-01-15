<?php

declare(strict_types=1);

namespace App\DataFixtures\Attendance;

use App\Entity\Attendance\Attendance;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AttendanceFixtures extends Fixture
{
    use TimeTrait;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $attendance = new Attendance(
                Uuid::fromString('2694264f-0e6f-40a6-9596-bc22d1eaa2c7'),
                'mission_1',
                76561198048200529
            );
            $manager->persist($attendance);

            $attendance = new Attendance(
                Uuid::fromString('d2a60cd4-d3f0-497a-9840-69fa5c388f1e'),
                'mission_2',
                76561198048200529
            );
            $manager->persist($attendance);

            $attendance = new Attendance(
                Uuid::fromString('0a918bf1-d20f-4856-aa0d-f093f6e90bf7'),
                'mission_3',
                76561198048200529
            );
            $manager->persist($attendance);

            $attendance = new Attendance(
                Uuid::fromString('2a3aa170-3a79-471f-b6ec-e194bf8d6c9e'),
                'mission_4',
                76561198048200529
            );
            $manager->persist($attendance);

            $attendance = new Attendance(
                Uuid::fromString('ac22546b-9d5c-4a54-a713-01dcc5ee40e6'),
                'mission_5',
                76561198048200529
            );
            $manager->persist($attendance);

            $manager->flush();
        });
    }
}
