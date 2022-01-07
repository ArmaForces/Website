<?php

declare(strict_types=1);

namespace App\SharedKernel\Infrastructure\Service\LegacyModListImport\Dto;

class ModCsvEntryDto
{
    public function __construct(
        private string $id,
        private string $name,
        private ?string $isServerSide,
        private ?string $isOptional,
        private ?string $isMap
    ) {
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIsServerSide(): ?string
    {
        return $this->isServerSide;
    }

    public function getIsOptional(): ?string
    {
        return $this->isOptional;
    }

    public function getIsMap(): ?string
    {
        return $this->isMap;
    }
}
