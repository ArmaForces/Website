<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Model\Traits;

interface DescribedInterface
{
    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
