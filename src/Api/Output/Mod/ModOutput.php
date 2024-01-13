<?php

declare(strict_types=1);

namespace App\Api\Output\Mod;

class ModOutput
{
    private ?string $id = null;
    private ?string $name = null;
    private ?\DateTimeInterface $createdAt = null;
    private ?\DateTimeInterface $lastUpdatedAt = null;
    private ?string $source = null;
    private ?string $status = null;
    private ?string $type = null;
    private ?int $itemId = null;
    private ?string $directory = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(?string $id): void
    {
        $this->id = $id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): void
    {
        $this->createdAt = $createdAt;
    }

    public function getLastUpdatedAt(): ?\DateTimeInterface
    {
        return $this->lastUpdatedAt;
    }

    public function setLastUpdatedAt(?\DateTimeInterface $lastUpdatedAt): void
    {
        $this->lastUpdatedAt = $lastUpdatedAt;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): void
    {
        $this->source = $source;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): void
    {
        $this->status = $status;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(?string $type): void
    {
        $this->type = $type;
    }

    public function getItemId(): ?int
    {
        return $this->itemId;
    }

    public function setItemId(?int $itemId): void
    {
        $this->itemId = $itemId;
    }

    public function getDirectory(): ?string
    {
        return $this->directory;
    }

    public function setDirectory(?string $directory): void
    {
        $this->directory = $directory;
    }
}
