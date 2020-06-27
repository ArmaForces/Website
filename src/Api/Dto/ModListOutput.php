<?php

declare(strict_types=1);

namespace App\Api\Dto;

class ModListOutput
{
    /**
     * @var null|string
     */
    protected $id;

    /**
     * @var null|string
     */
    protected $name;

    /**
     * @var null|\DateTimeInterface
     */
    protected $createdAt;

    /**
     * @var null|\DateTimeInterface
     */
    protected $lastUpdatedAt;

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
}
