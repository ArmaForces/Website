<?php

declare(strict_types=1);

namespace App\Users\DataFixtures\User;

use App\Shared\Test\Traits\TimeTrait;
use App\Users\Entity\Permissions\UserPermissions;
use App\Users\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class User2Fixture extends Fixture
{
    use TimeTrait;

    public const ID = '43c65dee-8da8-4198-87da-cb58ecc23508';
    public const USERNAME = 'user#2';
    public const EMAIL = 'user2@example.com';
    public const EXTERNAL_ID = '222222222222222222';
    public const STEAM_ID = 22222222222222222;

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $permissions = new UserPermissions(Uuid::fromString('97f1c390-46e8-47cd-b60d-e9cca1ae892e'));

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
