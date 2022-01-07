<?php

declare(strict_types=1);

namespace App\SharedKernel\Domain\Model;

use App\Entity\User\UserInterface;

abstract class AbstractBlamableEntity extends AbstractEntity implements BlamableEntityInterface
{
    protected ?UserInterface $createdBy = null;
    protected ?\DateTimeInterface $lastUpdatedAt = null;
    protected ?UserInterface $lastUpdatedBy = null;

    public function getCreatedBy(): ?UserInterface
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?UserInterface $createdBy): void
    {
        $this->createdBy = $createdBy;
    }

    public function getLastUpdatedAt(): ?\DateTimeInterface
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(?\DateTimeInterface $lastUpdatedAt): void
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    public function getLastUpdatedBy(): ?UserInterface
    {
        return $this->lastUpdatedBy;
    }

    public function setLastUpdatedBy(?UserInterface $lastUpdatedBy): void
    {
        $this->lastUpdatedBy = $lastUpdatedBy;
    }
}
