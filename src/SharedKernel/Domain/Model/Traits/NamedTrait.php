<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Model\Traits;

trait NamedTrait
{
    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }
}
