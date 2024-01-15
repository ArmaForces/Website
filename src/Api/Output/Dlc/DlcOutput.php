<?php

declare(strict_types=1);

namespace App\Api\Output\Dlc;

class DlcOutput
{
    private ?string $id = null;
    private ?string $name = null;
    private ?\DateTimeInterface $createdAt = null;
    private ?\DateTimeInterface $lastUpdatedAt = null;
    private ?int $appId = null;
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

    public function getAppId(): ?int
    {
        return $this->appId;
    }

    public function setAppId(?int $appId): void
    {
        $this->appId = $appId;
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
