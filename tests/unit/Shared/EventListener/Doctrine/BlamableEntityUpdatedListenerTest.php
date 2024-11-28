<?php

declare(strict_types=1);

namespace App\Tests\Unit\Shared\EventListener\Doctrine;

use App\Shared\Entity\Common\AbstractBlamableEntity;
use App\Shared\EventListener\Doctrine\BlamableEntityUpdatedListener;
use App\Users\Entity\User\User;
use Codeception\Test\Unit;
use Symfony\Bundle\SecurityBundle\Security;

class BlamableEntityUpdatedListenerTest extends Unit
{
    public function testMarkEntityAsUpdated(): void
    {
        $user = $this->createMock(User::class);
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn($user);

        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(self::once())
            ->method('updated')
            ->with(self::isInstanceOf(User::class))
        ;

        $blamableEntityUpdatedListener = new BlamableEntityUpdatedListener($security);
        $blamableEntityUpdatedListener->preUpdate($entity);
    }

    public function testDoNotMarkEntityAsUpdated(): void
    {
        $security = $this->createMock(Security::class);
        $security->method('getUser')->willReturn(null);

        $entity = $this->createMock(AbstractBlamableEntity::class);
        $entity
            ->expects(self::never())
            ->method('updated')
        ;

        $blamableEntityUpdatedListener = new BlamableEntityUpdatedListener($security);
        $blamableEntityUpdatedListener->preUpdate($entity);
    }
}
