<?php

declare(strict_types=1);

namespace App\DataFixtures\Mod\Directory\Deprecated;

use App\Entity\Mod\DirectoryMod;
use App\Entity\Mod\Enum\ModStatusEnum;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class R3ModFixture extends Fixture
{
    use TimeTrait;

    public const ID = '50b2c68a-1ea0-44b8-9b4d-6e0a47627d47';
    public const DIRECTORY = '@R3';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $mod = new DirectoryMod(
                Uuid::fromString(self::ID),
                'R3',
                null,
                ModStatusEnum::DEPRECATED,
                self::DIRECTORY
            );

            $manager->persist($mod);
            $manager->flush();

            $this->addReference(self::ID, $mod);
        });
    }
}
