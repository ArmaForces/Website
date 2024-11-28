<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\ModList\External;

use App\Mods\Entity\ModList\ExternalModList;
use App\Shared\Test\Traits\TimeTrait;
use App\Users\DataFixtures\User\User1Fixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class LocalhostExternalModList extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = '4900fe5f-3902-424f-bcc8-e92adbda4df5';
    public const NAME = 'Localhost';
    public const CREATED_BY_ID = User1Fixture::ID;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $createdBy = $this->getReference(self::CREATED_BY_ID);

            $externalModList = new ExternalModList(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                'https://localhost',
                true,
            );

            $externalModList->created($createdBy);

            $manager->persist($externalModList);
            $manager->flush();

            $this->addReference(self::ID, $externalModList);
        });
    }

    public function getDependencies(): array
    {
        return [
            User1Fixture::class, // created by
        ];
    }
}
