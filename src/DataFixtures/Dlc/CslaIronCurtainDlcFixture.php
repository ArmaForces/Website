<?php

declare(strict_types=1);

namespace App\DataFixtures\Dlc;

use App\Entity\Dlc\Dlc;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class CslaIronCurtainDlcFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'ebd772ce-e5b5-4813-9ad0-777915660d37';
    public const APP_ID = 1294440;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $dlc = new Dlc(
                Uuid::fromString(self::ID),
                'Arma 3 Creator DLC: CSLA Iron Curtain',
                null,
                self::APP_ID,
                'csla'
            );

            $manager->persist($dlc);
            $manager->flush();

            $this->addReference(self::ID, $dlc);
        });
    }
}
