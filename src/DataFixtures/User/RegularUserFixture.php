<?php

declare(strict_types=1);

namespace App\DataFixtures\User;

use App\Entity\Permissions\UserPermissions;
use App\Entity\User\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class RegularUserFixture extends Fixture
{
    public const ID = '03f6066e-2fe9-44ac-b853-2921483f1950';

    public function load(ObjectManager $manager): void
    {
        $permissions = new UserPermissions(Uuid::fromString('35874dd8-8230-41ca-ab18-1acb3419c177'));

        $user = new User(
            Uuid::fromString(self::ID),
            'regular#2222',
            'regular@example.com',
            self::ID,
            $permissions
        );

        $manager->persist($permissions);
        $manager->persist($user);
        $manager->flush();

        $this->addReference(self::ID, $user);
    }
}
