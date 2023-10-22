<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User\User;

use function Symfony\Component\Clock\now;

abstract class AbstractBlamableEntity extends AbstractEntity
{
    protected ?User $createdBy = null;
    protected ?\DateTimeInterface $lastUpdatedAt = null;
    protected ?User $lastUpdatedBy = null;

    public function created(User $user): void
    {
        $this->createdBy = $user;
    }

    public function updated(User $user): void
    {
        $this->lastUpdatedAt = now();
        $this->lastUpdatedBy = $user;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function getLastUpdatedAt(): ?\DateTimeInterface
    {
        return $this->lastUpdatedAt;
    }

    public function getLastUpdatedBy(): ?User
    {
        return $this->lastUpdatedBy;
    }
}
