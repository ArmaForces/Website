<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\Dlc;

use App\Mods\Entity\Dlc\Dlc;
use App\Shared\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class Spearhead1944DlcFixture extends Fixture
{
    use TimeTrait;

    public const ID = 'c42adf33-2f16-4bdf-bc38-66d7d037d677';
    public const APP_ID = 1175380;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $dlc = new Dlc(
                Uuid::fromString(self::ID),
                'Arma 3 Creator DLC: Spearhead 1944',
                null,
                self::APP_ID,
                'spe'
            );

            $manager->persist($dlc);
            $manager->flush();

            $this->addReference(self::ID, $dlc);
        });
    }
}
