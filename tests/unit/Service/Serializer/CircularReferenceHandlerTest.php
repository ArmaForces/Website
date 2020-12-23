<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Serializer;

use App\Entity\EntityInterface;
use App\Service\Serializer\CircularReferenceHandler;
use PHPUnit\Framework\TestCase;
use Ramsey\Uuid\Uuid;
use Symfony\Component\Serializer\Exception\CircularReferenceException;

/**
 * @internal
 * @covers \App\Service\Serializer\CircularReferenceHandler
 */
final class CircularReferenceHandlerTest extends TestCase
{
    /**
     * @test
     */
    public function invoke_supportedClass_returnsIdAsString(): void
    {
        $uuid = '541b5e52-22e7-497d-8e83-bff761cbfd5b';
        $id = $this->createMock(Uuid::class);
        $id->method('toString')->willReturn($uuid);

        $object = $this->createMock(EntityInterface::class);
        $object->method('getId')->willReturn($id);

        $circularReferenceHandler = new CircularReferenceHandler();
        $result = $circularReferenceHandler($object, 'json', []);

        static::assertSame($uuid, $result);
    }

    /**
     * @test
     */
    public function invoke_unsupportedClass_throwsException(): void
    {
        $object = $this->createMock(\stdClass::class);
        $circularReferenceHandler = new CircularReferenceHandler();

        $this->expectException(CircularReferenceException::class);
        $this->expectExceptionMessage(
            sprintf(
                'A circular reference has been detected when serializing the object of class "%s"',
                \get_class($object)
            )
        );

        $circularReferenceHandler($object, 'json', []);
    }
}
