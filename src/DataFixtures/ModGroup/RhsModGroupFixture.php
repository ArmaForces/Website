<?php

declare(strict_types=1);

namespace App\DataFixtures\ModGroup;

use App\Entity\ModGroup\ModGroup;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RhsModGroupFixture extends Fixture
{
    use TimeTrait;

    public const ID = '2f183e71-30c1-41c5-a555-acdf5fcf559e';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $modGroup = new ModGroup(
                Uuid::fromString(self::ID),
                'RHS',
                null,
                []
            );

            $manager->persist($modGroup);
            $manager->flush();

            $this->addReference(self::ID, $modGroup);
        });
    }
}
