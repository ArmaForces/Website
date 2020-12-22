<?php

declare(strict_types=1);

namespace App\Entity;

use App\Entity\User\UserInterface;
use Ramsey\Uuid\UuidInterface;

class AbstractEntity implements EntityInterface
{
    /** @var UuidInterface */
    protected $id;

    /** @var \DateTimeInterface */
    protected $createdAt;

    /** @var null|UserInterface */
    protected $createdBy;

    /** @var null|\DateTimeInterface */
    protected $lastUpdatedAt;

    /** @var null|UserInterface */
    protected $lastUpdatedBy;

    public function __construct(UuidInterface $id)
    {
        $this->id = $id;

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
