<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber\Doctrine;

use App\Entity\AbstractBlamableEntity;
use App\Entity\User\User;
use App\EventSubscriber\Doctrine\EntityBlamableSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

/**
 * @internal
 * @covers \App\EventSubscriber\Doctrine\EntityBlamableSubscriber
 */
final class EntityBlamableSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function getSubscribedEvents(): void
    {
        $security = $this->createMock(Security::class);
        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);

        $expectedEvents = [
            Events::prePersist,
            Events::preUpdate,
        ];

        $subscribedEvents = $entityBlamableSubscriberTest->getSubscribedEvents();
        static::assertSame($expectedEvents, $subscribedEvents);
    }

    /**
     * @test
     */
    public function prePersist_validEventArgs_entityUpdated(): void
    {
        $user = $this->createMock(User::class);
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(static::once())
            ->method('created')
            ->with(static::isInstanceOf(User::class))
        ;

        $lifecycleEventArgs = new PrePersistEventArgs($entity, $entityManager);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    /**
     * @test
     * @dataProvider invalidEventArgs
     */
    public function prePersist_invalidEventArgs_entityNotUpdated(mixed $user, mixed $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $lifecycleEventArgs = new PrePersistEventArgs($entity, $entityManager);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    /**
     * @test
     */
    public function preUpdate_validEventArgs_entityUpdated(): void
    {
        $user = $this->createMock(User::class);
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);
        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(static::once())
            ->method('updated')
            ->with(static::isInstanceOf(User::class))
        ;

        $changeSet = [];
        $lifecycleEventArgs = new PreUpdateEventArgs($entity, $entityManager, $changeSet);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    /**
     * @test
     * @dataProvider invalidEventArgs
     */
    public function preUpdate_invalidEventArgs_entityNotUpdated(mixed $user, mixed $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entityManager = $this->createMock(EntityManagerInterface::class);

        $changeSet = [];
        $lifecycleEventArgs = new PreUpdateEventArgs($entity, $entityManager, $changeSet);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    public function invalidEventArgs(): array
    {
        $validUser = $this->createMock(User::class);
        $invalidUser = null;

        $validEntity = $this->createMock(AbstractBlamableEntity::class);
        $validEntity->expects(static::never())->method('created');
        $validEntity->expects(static::never())->method('updated');

        $invalidEntity = $this->getMockBuilder(\stdClass::class)->getMock();
        $invalidEntity->expects(static::never())->method(static::anything());

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
