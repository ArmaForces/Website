<?php

declare(strict_types=1);

namespace App\DataFixtures\Dlc;

use App\Entity\Dlc\Dlc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class WesternSaharaFixture extends Fixture
{
    public const ID = '5f19a8b4-b879-48af-b9d6-73c21f846b3f';

    public function load(ObjectManager $manager): void
    {
        $dlc = new Dlc(
            Uuid::fromString(self::ID),
            'Arma 3 Creator DLC: Western Sahara',
            1681170,
            'ws'
        );

        $manager->persist($dlc);
        $manager->flush();

        $this->addReference(self::ID, $dlc);
    }
}
