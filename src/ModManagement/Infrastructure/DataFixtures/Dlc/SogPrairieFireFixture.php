<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\DataFixtures\Dlc;

use App\ModManagement\Domain\Model\Dlc\Dlc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class SogPrairieFireFixture extends Fixture
{
    public const ID = '805dfa49-ef6b-4259-85c5-a09565174448';

    public function load(ObjectManager $manager): void
    {
        $dlc = new Dlc(
            Uuid::fromString(self::ID),
            'Arma 3 Creator DLC: S.O.G. Prairie Fire',
            1227700,
            'vn'
        );

        $manager->persist($dlc);
        $manager->flush();

        $this->addReference(self::ID, $dlc);
    }
}
