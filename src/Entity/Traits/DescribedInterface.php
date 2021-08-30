<?php

declare(strict_types=1);

namespace App\Entity\Traits;

interface DescribedInterface
{
    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
