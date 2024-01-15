<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Directory;

use App\Entity\Mod\DirectoryMod;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class ArmaScriptProfilerModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '5506ae1b-2851-40e7-a15a-48f1fe6daaed';
    public const DIRECTORY = '@Arma Script Profiler';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new DirectoryMod(
                Uuid::fromString(self::ID),
                'Arma Script Profiler',
                null,
                null,
                self::DIRECTORY
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
