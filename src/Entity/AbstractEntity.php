<?php

declare(strict_types=1);

namespace App\Entity;

use Ramsey\Uuid\UuidInterface;

use function Symfony\Component\Clock\now;

abstract class AbstractEntity
{
    protected \DateTimeInterface $createdAt;

    public function __construct(
        protected UuidInterface $id,
    ) {
        $this->createdAt = now();
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getCreatedAt(): \DateTimeInterface
    {
        return $this->createdAt;
    }
}
