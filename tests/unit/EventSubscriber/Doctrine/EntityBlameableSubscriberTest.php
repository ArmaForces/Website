<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber\Doctrine;

use App\Entity\EntityInterface;
use App\Entity\User\UserInterface;
use App\EventSubscriber\Doctrine\EntityBlameableSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Security\Core\Security;

/**
 * @internal
 * @covers \App\EventSubscriber\Doctrine\EntityBlameableSubscriber
 */
final class EntityBlameableSubscriberTest extends TestCase
{
    /**
     * @test
     */
    public function getSubscribedEvents(): void
    {
        $security = $this->createMock(Security::class);
        $entityBlameableSubscriberTest = new EntityBlameableSubscriber($security);

        $expectedEvents = [
            Events::prePersist,
            Events::preUpdate,
        ];

        $subscribedEvents = $entityBlameableSubscriberTest->getSubscribedEvents();
        static::assertSame($expectedEvents, $subscribedEvents);
    }

    /**
     * @test
     */
    public function prePersist_validEventArgs_entityUpdated(): void
    {
        $user = $this->createMock(UserInterface::class);

        $entity = $this->createMock(EntityInterface::class);
        $entity
            ->expects(static::once())
            ->method('setCreatedBy')
            ->with(static::isInstanceOf(UserInterface::class))
        ;

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlameableSubscriberTest = new EntityBlameableSubscriber($security);
        $entityBlameableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    /**
     * @test
     * @dataProvider invalidEventArgs
     *
     * @param mixed $user
     * @param mixed $entity
     */
    public function prePersist_invalidEventArgs_entityUpdated($user, $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlameableSubscriberTest = new EntityBlameableSubscriber($security);
        $entityBlameableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    /**
     * @test
     */
    public function preUpdate_validEventArgs_entityUpdated(): void
    {
        $user = $this->createMock(UserInterface::class);

        $entity = $this->createMock(EntityInterface::class);
        $entity
            ->expects(static::once())
            ->method('setLastUpdatedBy')
            ->with(static::isInstanceOf(UserInterface::class))
        ;
        $entity
            ->expects(static::once())
            ->method('setLastUpdatedAt')
            ->with(static::isInstanceOf(\DateTimeInterface::class))
        ;

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlameableSubscriberTest = new EntityBlameableSubscriber($security);
        $entityBlameableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    /**
     * @test
     * @dataProvider invalidEventArgs
     *
     * @param mixed $user
     * @param mixed $entity
     */
    public function preUpdate_invalidEventArgs_entityUpdated($user, $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlameableSubscriberTest = new EntityBlameableSubscriber($security);
        $entityBlameableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    public function invalidEventArgs(): array
    {
        $validUser = $this->createMock(UserInterface::class);
        $invalidUser = null;

        $validEntity = $this->createMock(EntityInterface::class);
        $validEntity->expects(static::never())->method('setCreatedBy');
        $validEntity->expects(static::never())->method('setLastUpdatedBy');
        $validEntity->expects(static::never())->method('setLastUpdatedAt');

        $invalidEntity = $this->getMockBuilder(\stdClass::class)->setMethods([
            'setCreatedBy',
            'setLastUpdatedBy',
            'setLastUpdatedAt',
        ])->getMock();
        $invalidEntity->expects(static::never())->method('setCreatedBy');
        $invalidEntity->expects(static::never())->method('setLastUpdatedBy');
        $invalidEntity->expects(static::never())->method('setLastUpdatedAt');

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
