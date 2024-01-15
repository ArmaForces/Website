<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Entity\Permissions\UserPermissions;
use App\Entity\User\User;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AdminFixture extends Fixture
{
    use TimeTrait;

    public const ID = '31c9769f-4195-4fa8-b78d-0b54a1ceb8df';
    public const USERNAME = 'admin#0';
    public const EMAIL = 'admin@example.com';
    public const EXTERNAL_ID = '999999999999999999';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $permissions = new UserPermissions(Uuid::fromString('22dcc83c-8e72-48e4-8b04-debb6fbdd1c8'));
            $permissions->grantAll();

            $user = new User(
                Uuid::fromString(self::ID),
                self::USERNAME,
                self::EMAIL,
                self::EXTERNAL_ID,
                $permissions,
                [],
                null,
                null
            );

            $manager->persist($permissions);
            $manager->persist($user);
            $manager->flush();

            $this->addReference(self::ID, $user);
        });
    }
}
