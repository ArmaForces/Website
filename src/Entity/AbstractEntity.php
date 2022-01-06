<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

abstract class AbstractEntity implements EntityInterface
{
    protected \DateTimeInterface $createdAt;

    public function __construct(
        protected UuidInterface $id,
    ) {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }
}
