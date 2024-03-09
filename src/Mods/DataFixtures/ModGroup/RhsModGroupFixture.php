<?php

declare(strict_types=1);

namespace App\Mods\DataFixtures\ModGroup;

use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsAfrfModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsGrefModFixture;
use App\Mods\DataFixtures\Mod\SteamWorkshop\Required\RhsUsafModFixture;
use App\Mods\Entity\ModGroup\ModGroup;
use App\Shared\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RhsModGroupFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = '33191331-2a40-4d95-ba18-d4a75b1d43d4';
    public const NAME = 'RHS';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $modGroup = new ModGroup(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                [
                    $this->getReference(RhsAfrfModFixture::ID),
                    $this->getReference(RhsGrefModFixture::ID),
                    $this->getReference(RhsUsafModFixture::ID),
                ]
            );

            $manager->persist($modGroup);
            $manager->flush();

            $this->addReference(self::ID, $modGroup);
        });
    }

    public function getDependencies(): array
    {
        return [
            RhsAfrfModFixture::class,
            RhsGrefModFixture::class,
            RhsUsafModFixture::class,
        ];
    }
}
