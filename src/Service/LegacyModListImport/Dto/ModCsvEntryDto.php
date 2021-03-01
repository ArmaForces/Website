<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport\Dto;

class ModCsvEntryDto
{
    protected string $id;
    protected string $name;
    protected ?string $isServerSide;
    protected ?string $isOptional;
    protected ?string $isMap;

    public function __construct(string $id, string $name, ?string $isServerSide, ?string $isOptional, ?string $isMap)
    {
        $this->id = $id;
        $this->name = $name;
        $this->isServerSide = $isServerSide;
        $this->isOptional = $isOptional;
        $this->isMap = $isMap;
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
