<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\ModList\External;

use App\Mods\Entity\ModList\ExternalModList;
use App\Shared\Test\Traits\TimeTrait;
use App\Users\DataFixtures\User\User2Fixture;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class GoogleExternalModList extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = '296cc791-c73f-4978-b377-da1d3aa28cfb';
    public const NAME = 'Google';
    public const CREATED_BY_ID = User2Fixture::ID;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $createdBy = $this->getReference(self::CREATED_BY_ID);

            $externalModList = new ExternalModList(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                'https://google.com',
                false,
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
            User2Fixture::class, // created by
        ];
    }
}
