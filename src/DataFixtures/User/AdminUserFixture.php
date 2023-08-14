<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Entity\Permissions\UserPermissions;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AdminUserFixture extends Fixture
{
    public const ID = '31c9769f-4195-4fa8-b78d-0b54a1ceb8df';

    public function load(ObjectManager $manager): void
    {
        $permissions = new UserPermissions(Uuid::fromString('22dcc83c-8e72-48e4-8b04-debb6fbdd1c8'));
        $permissions->grantAll();

        $user = new User(
            Uuid::fromString(self::ID),
            'admin#1111',
            'admin@example.com',
            self::ID,
            $permissions,
            [],
            null,
            null
        );

        $manager->persist($permissions);
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::ID, $user);
    }
}
