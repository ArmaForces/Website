<?php

declare(strict_types=1);

namespace App\Entity;

interface EntityInterface
{
    public function getId(): string;

    public function getCreatedAt(): \DateTimeInterface;

    public function setCreatedAt(\DateTimeInterface $createdAt): void;

    public function getLastUpdatedAt(): ?\DateTimeInterface;

    public function setLastUpdatedAt(?\DateTimeInterface $lastUpdatedAt): void;
}
