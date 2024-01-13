<?php

declare(strict_types=1);

namespace App\DataFixtures\UserGroup;

use App\DataFixtures\User\AdminFixture;
use App\Entity\Permissions\UserGroupPermissions;
use App\Entity\UserGroup\UserGroup;
use App\Test\Traits\TimeTrait;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;
use Doctrine\Persistence\ObjectManager;
use Ramsey\Uuid\Uuid;

class AdminsGroupFixture extends Fixture implements DependentFixtureInterface
{
    use TimeTrait;

    public const ID = '8265e3ee-fed0-4f34-928a-b3225d70e901';
    public const NAME = 'Admins';

    public function load(ObjectManager $manager): void
    {
        $this->withTimeFrozenAt('2020-01-01T00:00:00+00:00', function () use ($manager): void {
            $permissions = new UserGroupPermissions(Uuid::fromString('c5b9af26-29e0-41f5-828f-00563f875d6e'));

            $userGroup = new UserGroup(
                Uuid::fromString(self::ID),
                self::NAME,
                null,
                $permissions,
                [
                    $this->getReference(AdminFixture::ID),
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
            AdminFixture::class,
        ];
    }
}
