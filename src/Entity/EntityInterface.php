<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User\UserInterface;
use Ramsey\Uuid\UuidInterface;

interface EntityInterface
{
    public function getId(): UuidInterface;

    public function getCreatedAt(): \DateTimeInterface;

    public function setCreatedAt(\DateTimeInterface $createdAt): void;

    public function getCreatedBy(): ?UserInterface;

    public function setCreatedBy(?UserInterface $createdBy): void;

    public function getLastUpdatedAt(): ?\DateTimeInterface;

    public function setLastUpdatedAt(?\DateTimeInterface $lastUpdatedAt): void;

    public function getLastUpdatedBy(): ?UserInterface;

    public function setLastUpdatedBy(?UserInterface $lastUpdatedBy): void;
}
