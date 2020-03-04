<?php

declare(strict_types=1);

namespace App\Entity;

interface DescribedEntityInterface extends NamedEntityInterface
{
    public function getDescription(): ?string;

    public function setDescription(?string $description): void;
}
