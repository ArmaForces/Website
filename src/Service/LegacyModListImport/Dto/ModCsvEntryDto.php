<?php

declare(strict_types=1);

namespace App\Service\LegacyModListImport\Dto;

class ModCsvEntryDto
{
    /** @var string */
    protected $id;

    /** @var string */
    protected $name;

    /** @var null|string */
    protected $isServerSide;

    /** @var null|string */
    protected $isOptional;

    /** @var null|string */
    protected $isMap;

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
