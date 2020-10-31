<?php

declare(strict_types=1);

namespace App\Tests\Unit\Service\Serializer;

use App\Entity\EntityInterface;
use App\Service\Serializer\CircularReferenceHandler;
use PHPUnit\Framework\TestCase;
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
        $id = '541b5e52-22e7-497d-8e83-bff761cbfd5b';
        $class = $this->createMock(EntityInterface::class);
        $class->method('getId')->willReturn($id);

        $circularReferenceHandler = new CircularReferenceHandler();
        $result = $circularReferenceHandler($class, 'json', []);

        static::assertSame($id, $result);
    }

    /**
     * @test
     */
    public function invoke_unsupportedClass_throwsException(): void
    {
        $class = $this->createMock(\stdClass::class);
        $circularReferenceHandler = new CircularReferenceHandler();

        $this->expectException(CircularReferenceException::class);
        $this->expectExceptionMessage(
            sprintf(
                'A circular reference has been detected when serializing the object of class "%s"',
                \get_class($class)
            )
        );

        $circularReferenceHandler($class, 'json', []);
    }
}
