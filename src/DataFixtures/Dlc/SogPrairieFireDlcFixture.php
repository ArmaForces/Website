<?php

declare(strict_types=1);

namespace App\DataFixtures\Dlc;

use App\Entity\Dlc\Dlc;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class SogPrairieFireDlcFixture extends Fixture
{
    use TimeTrait;

    public const ID = '805dfa49-ef6b-4259-85c5-a09565174448';
    public const APP_ID = 1227700;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $dlc = new Dlc(
                Uuid::fromString(self::ID),
                'Arma 3 Creator DLC: S.O.G. Prairie Fire',
                null,
                self::APP_ID,
                'vn'
            );

            $manager->persist($dlc);
            $manager->flush();

            $this->addReference(self::ID, $dlc);
        });
    }
}
