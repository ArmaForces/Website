<?php

declare(strict_types=1);

namespace App\Tests\Unit\EventSubscriber\Doctrine;

use App\Entity\BlamableEntityInterface;
use App\Entity\User\UserInterface;
use App\EventSubscriber\Doctrine\EntityBlamableSubscriber;
use Doctrine\ORM\Event\LifecycleEventArgs;
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
        $this::assertSame($expectedEvents, $subscribedEvents);
    }

    /**
     * @test
     */
    public function prePersist_validEventArgs_entityUpdated(): void
    {
        $user = $this->createMock(UserInterface::class);

        $entity = $this->createMock(BlamableEntityInterface::class);
        $entity
            ->expects($this::once())
            ->method('setCreatedBy')
            ->with($this::isInstanceOf(UserInterface::class))
        ;

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    /**
     * @test
     * @dataProvider invalidEventArgs
     *
     * @param mixed $user
     * @param mixed $entity
     */
    public function prePersist_invalidEventArgs_entityNotUpdated($user, $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->prePersist($lifecycleEventArgs);
    }

    /**
     * @test
     */
    public function preUpdate_validEventArgs_entityUpdated(): void
    {
        $user = $this->createMock(UserInterface::class);

        $entity = $this->createMock(BlamableEntityInterface::class);
        $entity
            ->expects($this::once())
            ->method('setLastUpdatedBy')
            ->with($this::isInstanceOf(UserInterface::class))
        ;
        $entity
            ->expects($this::once())
            ->method('setLastUpdatedAt')
            ->with($this::isInstanceOf(\DateTimeInterface::class))
        ;

        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    /**
     * @test
     * @dataProvider invalidEventArgs
     *
     * @param mixed $user
     * @param mixed $entity
     */
    public function preUpdate_invalidEventArgs_entityNotUpdated($user, $entity): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $lifecycleEventArgs = $this->createMock(LifecycleEventArgs::class);
        $lifecycleEventArgs->method('getEntity')->willReturn($entity);

        $entityBlamableSubscriberTest = new EntityBlamableSubscriber($security);
        $entityBlamableSubscriberTest->preUpdate($lifecycleEventArgs);
    }

    public function invalidEventArgs(): array
    {
        $validUser = $this->createMock(UserInterface::class);
        $invalidUser = null;

        $validEntity = $this->createMock(BlamableEntityInterface::class);
        $validEntity->expects($this::never())->method('setCreatedBy');
        $validEntity->expects($this::never())->method('setLastUpdatedBy');
        $validEntity->expects($this::never())->method('setLastUpdatedAt');

        $invalidEntity = $this->getMockBuilder(\stdClass::class)->setMethods([
            'setCreatedBy',
            'setLastUpdatedBy',
            'setLastUpdatedAt',
        ])->getMock();
        $invalidEntity->expects($this::never())->method('setCreatedBy');
        $invalidEntity->expects($this::never())->method('setLastUpdatedBy');
        $invalidEntity->expects($this::never())->method('setLastUpdatedAt');

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