<?php

declare(strict_types=1);

namespace App\ModManagement\Infrastructure\DataFixtures\ModGroup;

use App\ModManagement\Domain\Model\ModGroup\ModGroup;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RhsModGroupFixture extends Fixture
{
    public const ID = '2f183e71-30c1-41c5-a555-acdf5fcf559e';

    public function load(ObjectManager $manager): void
    {
        $modGroup = new ModGroup(
            Uuid::fromString(self::ID),
            'RHS'
        );
        $modGroup->setCreatedAt(\DateTimeImmutable::createFromFormat('Y-m-d H:i:s', '2020-01-01 00:00:00'));

        $manager->persist($modGroup);
        $manager->flush();

        $this->addReference(self::ID, $modGroup);
    }
}
