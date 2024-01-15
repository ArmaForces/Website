<?php

declare(strict_types=1);

namespace App\DataFixtures\Dlc;

use App\Entity\Dlc\Dlc;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class GlobalMobilizationDlcFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'c2cd8ffd-0b4b-449b-aca5-cb91f16a9e54';
    public const APP_ID = 1042220;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $dlc = new Dlc(
                Uuid::fromString(self::ID),
                'Arma 3 Creator DLC: Global Mobilization - Cold War Germany',
                null,
                self::APP_ID,
                'gm'
            );

            $manager->persist($dlc);
            $manager->flush();

            $this->addReference(self::ID, $dlc);
        });
    }
}
