<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\EventSubscriber\Doctrine;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Shared\EventSubscriber\Doctrine\EntityBlamableSubscriber;
use App\Users\Entity\User\User;
use Codeception\Attribute\DataProvider;
use Codeception\Test\Unit;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use Symfony\Bundle\SecurityBundle\Security;

final class EntityBlamableSubscriberTest extends Unit
{
    public function testGetSubscribedEvents(): void
    {
        $security = $this->createMock(Security::class);
        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);

        $expectedEvents = [
            Events::prePersist,
            Events::preUpdate,
        ];

        $subscribedEvents = $entityBlamableSubscriberTest->getSubscribedEvents();
        self::assertSame($expectedEvents, $subscribedEvents);
    }

    public function testMarkEntityAsCreated(): void
    {
        $user = $this->createMock(User::class);
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(self::once())
            ->method('created')
            ->with(self::isInstanceOf(User::class))
        ;

        $lifecycleEventArgs = new PrePersistEventArgs($entity, $entityManager);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    #[DataProvider('invalidEventArgs')]
    public function testDoNotMarkEntityAsCreated(mixed $user, mixed $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $lifecycleEventArgs = new PrePersistEventArgs($entity, $entityManager);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    public function testMarkEntityAsUpdated(): void
    {
        $user = $this->createMock(User::class);
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(self::once())
            ->method('updated')
            ->with(self::isInstanceOf(User::class))
        ;

        $changeSet = [];
        $lifecycleEventArgs = new PreUpdateEventArgs($entity, $entityManager, $changeSet);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    #[DataProvider('invalidEventArgs')]
    public function testDoNotMarkEntityAsUpdated(mixed $user, mixed $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $changeSet = [];
        $lifecycleEventArgs = new PreUpdateEventArgs($entity, $entityManager, $changeSet);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    protected function invalidEventArgs(): iterable
    {
        $validUser = $this->createMock(User::class);
        $invalidUser = null;

        $validEntity = $this->createMock(AbstractBlamableEntity::class);
        $validEntity->expects(self::never())->method('created');
        $validEntity->expects(self::never())->method('updated');

        $invalidEntity = $this->getMockBuilder(\stdClass::class)->getMock();
        $invalidEntity->expects(self::never())->method(self::anything());

        return [
            'valid user, invalid entity' => [
                $validUser,
                $invalidEntity,
            ],
            'invalid user, valid entity' => [
                $invalidUser,
                $validEntity,
            ],
        ];
    }
}
