<?php

declare(strict_types=1);

namespace App\DataFixtures\Dlc;

use App\Entity\Dlc\Dlc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class GlobalMobilizationFixture extends Fixture
{
    public const ID = 'c2cd8ffd-0b4b-449b-aca5-cb91f16a9e54';

    public function load(ObjectManager $manager): void
    {
        $dlc = new Dlc(
            Uuid::fromString(self::ID),
            'Arma 3 Creator DLC: Global Mobilization - Cold War Germany',
            1042220,
            'gm'
        );

        $manager->persist($dlc);
        $manager->flush();

        $this->addReference(self::ID, $dlc);
    }
}
