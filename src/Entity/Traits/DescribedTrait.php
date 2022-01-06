<?php

declare(strict_types=1);

namespace App\Entity\Traits;

trait DescribedTrait
{
    private ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
