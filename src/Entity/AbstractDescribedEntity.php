<?php

declare(strict_types=1);

namespace App\Entity;

abstract class AbstractDescribedEntity extends AbstractNamedEntity implements DescribedEntityInterface
{
    protected ?string $description = null;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
