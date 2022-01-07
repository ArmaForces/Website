<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Model;

use App\Entity\User\UserInterface;

interface BlamableEntityInterface extends EntityInterface
{
    public function getCreatedBy(): ?UserInterface;

    public function setCreatedBy(?UserInterface $createdBy): void;

    public function getLastUpdatedAt(): ?\DateTimeInterface;

    public function setLastUpdatedAt(?\DateTimeInterface $lastUpdatedAt): void;

    public function getLastUpdatedBy(): ?UserInterface;

    public function setLastUpdatedBy(?UserInterface $lastUpdatedBy): void;
}
