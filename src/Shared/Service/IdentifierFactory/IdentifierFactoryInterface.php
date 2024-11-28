<?php

declare(strict_types=1);

namespace App\Shared\Service\IdentifierFactory;

use Ramsey\Uuid\UuidInterface;

interface IdentifierFactoryInterface
{
    public function create(): UuidInterface;
}
