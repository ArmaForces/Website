<?php

declare(strict_types=1);

namespace App\DataFixtures\Dlc;

use App\Entity\Dlc\Dlc;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CslaIronCurtain extends Fixture
{
    public const ID = 'ebd772ce-e5b5-4813-9ad0-777915660d37';

    public function load(ObjectManager $manager): void
    {
        $dlc = new Dlc(
            Uuid::fromString(self::ID),
            'Arma 3 Creator DLC: CSLA Iron Curtain',
            null,
            1294440,
            'csla'
        );

        $manager->persist($dlc);
        $manager->flush();

        $this->addReference(self::ID, $dlc);
    }
}
