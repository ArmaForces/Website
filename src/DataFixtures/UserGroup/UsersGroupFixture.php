<?php

declare(strict_types=1);

namespace App\DataFixtures\UserGroup;

use App\DataFixtures\User\User1Fixture;
use App\DataFixtures\User\User2Fixture;
use App\DataFixtures\User\User3Fixture;
use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\UserGroup\UserGroup;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class UsersGroupFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = 'e2285b93-235f-4c79-b5d7-cb0fc9cc1fbc';
    public const NAME = 'Users';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $permissions = new UserGroupPermissions(Uuid::fromString('323ebe3d-4f20-404e-9b92-c1833405947a'));

            $userGroup = new UserGroup(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                $permissions,
                [
                    $this->getReference(User1Fixture::ID),
                    $this->getReference(User2Fixture::ID),
                    $this->getReference(User3Fixture::ID),
                ]
            );

            $manager->persist($permissions);
            $manager->persist($userGroup);
            $manager->flush();

            $this->addReference(self::ID, $userGroup);
        });
    }

    public function getDependencies(): array
    {
        return [
            User1Fixture::class,
            User2Fixture::class,
            User3Fixture::class,
        ];
    }
}
