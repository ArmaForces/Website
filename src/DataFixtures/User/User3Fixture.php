<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Entity\Permissions\UserPermissions;
use App\Entity\User\User;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class User3Fixture extends Fixture
{
    use TimeTrait;

    public const ID = '7e1a0cae-e0c3-4b1d-9ce7-671505267a87';
    public const USERNAME = 'user#3';
    public const EMAIL = 'user3@example.com';
    public const EXTERNAL_ID = '333333333333333333';
    public const STEAM_ID = 33333333333333333;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $permissions = new UserPermissions(Uuid::fromString('7dcfba43-a09c-46c0-9dbd-701442bd4f83'));

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
