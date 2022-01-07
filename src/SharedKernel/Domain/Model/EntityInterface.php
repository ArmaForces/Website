<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Model;

use Ramsey\Uuid\UuidInterface;

interface EntityInterface
{
    public function getId(): UuidInterface;

    public function getCreatedAt(): \DateTimeInterface;

    public function setCreatedAt(\DateTimeInterface $createdAt): void;
}
