<?php

declare(strict_types=1);

namespace App\Pmc\Service\Dynulo\Dto;

class ItemDto
{
    /** @var string */
    protected $class;

    /** @var string */
    protected $pretty;

    /** @var int */
    protected $cost;

    /** @var array<string> */
    protected $traits;

    /** @var \DateTimeImmutable */
    protected $created;

    /**
     * @param array<string> $traits
     */
    public function __construct(string $class, string $pretty, int $cost, array $traits, \DateTimeImmutable $created)
    {
        $this->class = $class;
        $this->pretty = $pretty;
        $this->cost = $cost;
        $this->traits = $traits;
        $this->created = $created;
    }

    public static function fromArray(array $array): self
    {
        $timezone = new \DateTimeZone('Europe/London');

        return new self(
            $array['class'],
            $array['pretty'],
            $array['cost'],
            $array['traits'] !== '' ? \explode('|', $array['traits']) : [],
            \DateTimeImmutable::createFromFormat('Y-m-d\TH:i:s', substr($array['created'], 0, 19), $timezone),
        );
    }

    public function getClass(): string
    {
        return $this->class;
    }

    public function getPretty(): string
    {
        return $this->pretty;
    }

    public function getCost(): int
    {
        return $this->cost;
    }

    public function getTraits(): array
    {
        return $this->traits;
    }

    public function getCreated(): \DateTimeImmutable
    {
        return $this->created;
    }
}
