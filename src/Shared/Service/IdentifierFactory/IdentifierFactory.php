<?php

declare(strict_types=1);

namespace App\Shared\Service\IdentifierFactory;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;

class IdentifierFactory implements IdentifierFactoryInterface
{
    public function create(): UuidInterface
    {
        return Uuid::uuid4();
    }
}
