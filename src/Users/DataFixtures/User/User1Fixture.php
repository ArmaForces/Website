<?php

declare(strict_types=1);

namespace App\Users\DataFixtures\User;

use App\Shared\Test\Traits\TimeTrait;
use App\Users\Entity\Permissions\UserPermissions;
use App\Users\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class User1Fixture extends Fixture
{
    use TimeTrait;

    public const ID = '03f6066e-2fe9-44ac-b853-2921483f1950';
    public const USERNAME = 'user#1';
    public const EMAIL = 'user1@example.com';
    public const EXTERNAL_ID = '111111111111111111';
    public const STEAM_ID = 11111111111111111;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $permissions = new UserPermissions(Uuid::fromString('b62733f6-97dc-4031-b74a-ecb080c09658'));

            $user = new User(
                Uuid::fromString(self::ID),
                self::USERNAME,
                self::EMAIL,
                self::EXTERNAL_ID,
                $permissions,
                [],
                null,
                self::STEAM_ID
            );

            $manager->persist($permissions);
            $manager->persist($user);
            $manager->flush();

            $this->addReference(self::ID, $user);
        });
    }
}
