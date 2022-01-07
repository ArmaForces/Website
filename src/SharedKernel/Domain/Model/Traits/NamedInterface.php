<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Model\Traits;

interface NamedInterface
{
    public function getName(): string;

    public function setName(string $name): void;
}
