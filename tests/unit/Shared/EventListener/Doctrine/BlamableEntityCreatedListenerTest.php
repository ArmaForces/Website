<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\EventListener\Doctrine;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Shared\EventListener\Doctrine\BlamableEntityCreatedListener;
use App\Users\Entity\User\User;
use Codeception\Test\Unit;
use Symfony\Bundle\SecurityBundle\Security;

class BlamableEntityCreatedListenerTest extends Unit
{
    public function testMarkEntityAsCreated(): void
    {
        $user = $this->createMock(User::class);
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(self::once())
            ->method('created')
            ->with(self::isInstanceOf(User::class))
        ;

        $blamableEntityCreatedListener = new BlamableEntityCreatedListener($security);
        $blamableEntityCreatedListener->prePersist($entity);
    }

    public function testDoNotMarkEntityAsCreated(): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn(null);

        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(self::never())
            ->method('created')
        ;

        $blamableEntityCreatedListener = new BlamableEntityCreatedListener($security);
        $blamableEntityCreatedListener->prePersist($entity);
    }
}
