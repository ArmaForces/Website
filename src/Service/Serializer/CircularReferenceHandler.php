<?php

declare(strict_types=1);

namespace App\Service\Serializer;

use App\Entity\EntityInterface;
use Symfony\Component\Serializer\Exception\CircularReferenceException;

class CircularReferenceHandler
{
    public function __invoke($object, string $format, array $context): string
    {
        if ($object instanceof EntityInterface) {
            return $object->getId();
        }

        throw new CircularReferenceException(
            sprintf(
                'A circular reference has been detected when serializing the object of class "%s"',
                \get_class($object)
            )
        );
    }
}
