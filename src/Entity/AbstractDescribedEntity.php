<?php

declare(strict_types=1);

namespace App\Entity;

class AbstractDescribedEntity extends AbstractNamedEntity implements DescribedEntityInterface
{
    /** @var null|string */
    protected $description;

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }
}
